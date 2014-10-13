<?php

namespace Tutto\Bundle\UtilBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class AbstractEntityRepository
 * @package Tutto\Bundle\UtilBundle\Repository
 */
abstract class AbstractEntityRepository extends EntityRepository {
    public function update($entity) {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}