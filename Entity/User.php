<?php

namespace Tutto\Bundle\UtilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Tutto\Bundle\UtilBundle\Logic\Status;

/**
 * Class User
 * @package Tutto\Bundle\UtilBundle\Entity
 *
 * @ORM\Table(name="users")
 * @ORM\MappedSuperclass()
 */
class User extends AbstractEntity implements UserInterface {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(length=255)
     * @Assert\Email()
     *
     * @var string
     */
    protected $username;

    /**
     * @ORM\Column(length=255)
     *
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $salt;

    /**
     * @ORM\ManyToOne(targetEntity="Tutto\Bundle\UtilBundle\Entity\Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var Role
     */
    protected $role;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $activationCode = null;

    /**
     * @ORM\OneToOne(targetEntity="Tutto\Bundle\UtilBundle\Entity\Person", cascade={"all"})
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     *
     * @var Person
     */
    protected $person;

    /**
     * @Assert\Email()
     *
     * @var string
     */
    protected $plainUsername;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return Role[] The user roles
     */
    public function getRoles() {
        return array($this->role->getName());
    }

    /**
     * @return string The password
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @return string|null The salt
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * @return string The username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role) {
        $this->role = $role;
    }

    /**
     * @return Role
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person) {
        $this->person = $person;
    }

    /**
     * @return Person
     */
    public function getPerson() {
        return $this->person;
    }

    /**
     * @return string
     */
    public function getActivationCode() {
        return $this->activationCode;
    }

    /**
     * @param string $activationCode
     */
    public function setActivationCode($activationCode) {
        $this->activationCode = $activationCode;
    }

    /**
     * @return string
     */
    public function getPlainUsername() {
        return $this->plainUsername;
    }

    /**
     * @param string $plainUsername
     */
    public function setPlainUsername($plainUsername) {
        $this->plainUsername = $plainUsername;
    }

    /**
     * @return string
     */
    public function getPlainPassword() {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return bool
     */
    public function isActivated() {
        return $this->getStatus() === Status::ENABLED && $this->getActivationCode() === null;
    }

    public function eraseCredentials() { }
}