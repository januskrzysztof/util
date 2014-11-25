<?php

namespace Tutto\Bundle\UtilBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Tutto\Bundle\UtilBundle\Entity\Role;

/**
 * Class RoleRepository
 * @package Tutto\Bundle\UtilBundle\Repository
 */
class RoleRepository extends EntityRepository {
    /**
     * @param string  $name
     * @return null|Role
     */
    public function findByName($name) {
        return $this->findOneBy(['name' => $name]);
    }
}