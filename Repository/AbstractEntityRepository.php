<?php

namespace Tutto\Bundle\UtilBundle\Repository;

use Doctrine\ORM\EntityRepository;
use DateTime;

use Tutto\Bundle\UtilBundle\Entity\AbstractEntity;

/**
 * Class AbstractEntityRepository
 * @package Tutto\Bundle\UtilBundle\Repository
 */
abstract class AbstractEntityRepository extends EntityRepository {
    /**
     * @param mixed $entity
     */
    public function update($entity) {
        if ($entity instanceof AbstractEntity) {
            if ($entity->getCreatedAt() === null) {
                $entity->setCreatedAt(new DateTime());
            }

            $entity->setModifiedAt(new DateTime());
        }

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}