<?php

namespace Tutto\Bundle\UtilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

use Tutto\Bundle\UtilBundle\Logic\Status;

/**
 * Class AbstractEntity
 * @package Tutto\Bundle\UtilBundle\Entity
 *
 * @ORM\MappedSuperclass()
 */
class AbstractEntity {
    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default": "1990-01-01 00:00:00"})
     * @Assert\DateTime()
     *
     * @var DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": "1990-01-01 00:00:00"})
     * @Assert\DateTime()
     *
     * @var DateTime
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default": 1})
     * @Assert\GreaterThanOrEqual(0)
     *
     * @var int
     */
    private $status = Status::ENABLED;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": 0})
     *
     * @var bool
     */
    private $isDeleted = false;

    public function __construct() {
        $this->createdAt  = new DateTime();
        $this->modifiedAt = new DateTime();
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getModifiedAt() {
        return $this->modifiedAt;
    }

    /**
     * @param DateTime $modifiedAt
     */
    public function setModifiedAt(DateTime $modifiedAt) {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status) {
        $this->status = (int) $status;
    }

    /**
     * @return bool
     */
    public function isDeleted() {
        return $this->isDeleted;
    }

    /**
     * @param boolean $isDeleted
     */
    public function setIsDeleted($isDeleted) {
        $this->isDeleted = (boolean) $isDeleted;
    }
}