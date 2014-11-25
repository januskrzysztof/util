<?php

namespace Tutto\Bundle\UtilBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Tutto\Bundle\UtilBundle\Entity\User;
use Tutto\Bundle\UtilBundle\Exceptions\BadUserClassException;
use Tutto\Bundle\UtilBundle\Exceptions\UserDeletedException;
use Tutto\Bundle\UtilBundle\Exceptions\UsernameOrPasswordEmptyException;
use Tutto\Bundle\UtilBundle\Exceptions\UserNotActivatedException;
use Tutto\Bundle\UtilBundle\Logic\Status;
use LogicException;
use ReflectionObject;

/**
 * Class UserRepository
 * @package Tutto\Bundle\UtilBundle\Repository
 */
class UserRepository extends EntityRepository implements UserProviderInterface {
    const DELETE_PERMANENTLY = 1;
    const DELETE_TEMPORARILY = 2;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @return User
     */
    public function createUser() {
        $user = new User();
        $user->setActivationCode($this->generate());
        $user->setStatus(Status::ARCHIVED);
        $this->setValueToUserProperty($user, 'salt', $this->generate());

        return $user;
    }

    /**
     * @param User $user
     * @param bool $andFlush
     */
    public function updateUser(User $user, $andFlush = true) {
        $this->getEntityManager()->persist($user);
        if ($andFlush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param User $user
     * @param int $deleteMode
     * @throws LogicException
     */
    public function deleteUser(User $user, $deleteMode = self::DELETE_TEMPORARILY) {
        if ($deleteMode === self::DELETE_TEMPORARILY) {
            $user->setIsDeleted(true);
            $this->getEntityManager()->persist($user);
        } elseif ($deleteMode = self::DELETE_PERMANENTLY) {
            $this->getEntityManager()->remove($user);
        } else {
            throw new LogicException("Delete mode: '{$deleteMode}' is not valid delete mode.");
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @param User $user
     * @throws UsernameOrPasswordEmptyException
     */
    public function updateCanonicalUsername(User $user) {
        if (!empty($user->getPlainUsername())) {
            $this->setValueToUserProperty($user, 'username', $user->getPlainUsername());
        } else {
            throw new UsernameOrPasswordEmptyException("Plain username (method: 'getPlainUsername()') can not be empty!");
        }
    }

    /**
     * @param User $user
     * @throws UsernameOrPasswordEmptyException
     */
    public function updateCanonicalPassword(User $user) {
        if (!empty($user->getPlainPassword())) {
            $encoder = $this->getEncoderFactory()->getEncoder(User::class);
            $password = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());

            $this->setValueToUserProperty($user, 'password', $password);
        } else {
            throw new UsernameOrPasswordEmptyException("Plain password (method: 'getPlainPassword()') can not be empty!");
        }
    }

    /**
     * @param User $user
     * @throws UsernameOrPasswordEmptyException
     */
    public function updateCanonical(User $user) {
        $this->updateCanonicalPassword($user);
        $this->updateCanonicalUsername($user);
    }

    /**
     * @param int $max
     * @return string
     */
    public function generate($max = 32) {
        return substr(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36), 0, $max);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function findById($id) {
        return $this->find((int) $id);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return User[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return User|null
     */
    public function findOneBy(array $criteria, array $orderBy = null) {
        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * @return User[]
     */
    public function findAll() {
        return parent::findAll();
    }

    /**
     * @param string $username The username
     * @return User
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username) {
        $query = $this->createQueryBuilder('user')
            ->select('user, role, person, address, correspondenceAddress')
            ->innerJoin('user.role', 'role')
            ->innerJoin('user.person', 'person')
            ->leftJoin('person.address', 'address')
            ->leftJoin('person.correspondenceAddress', 'correspondenceAddress')
            ->where("user.username = '{$username}'");

        try {

            /** @var User $user */
            $user = $query->getQuery()->getSingleResult();
        } catch (NoResultException $ex) {
            $user = null;
        }

        if (!$user) {
            throw new UsernameNotFoundException();
        }
        if (!$user instanceof User) {
            throw new BadUserClassException();
        }
        if ($user->isDeleted()) {
            throw new UserDeletedException();
        }
        if (!$user->isActivated()) {
            throw new UserNotActivatedException();
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user) {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class) {
        return $class === User::class;
    }

    /**
     * @return EncoderFactoryInterface
     */
    public function getEncoderFactory() {
        return $this->encoderFactory;
    }

    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function setEncoderFactory(EncoderFactoryInterface $encoderFactory) {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param User $user
     * @param string $property
     * @param mixed $value
     */
    protected function setValueToUserProperty(User $user, $property, $value) {
        $reflection = new ReflectionObject($user);

        if ($reflection->hasProperty($property)) {
            $property = $reflection->getProperty($property);
            $property->setAccessible(true);
            $property->setValue($user, $value);
        } else {
            throw new NoSuchPropertyException("Property: '{$property}' for class: '".get_class($user)."' not found.");
        }
    }
}