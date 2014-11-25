<?php

namespace Tutto\Bundle\UtilBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Role
 * @package Tutto\Bundle\UtilBundle\Entity
 *
 * @ORM\Table(name="roles")
 * @ORM\MappedSuperclass()
 */
class Role {
    const ADMIN  = 'ADMIN';
    const MEMBER = 'MEMBER';

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
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(length=255)
     *
     * @var string
     */
    protected $alias;

    /**
     * @ORM\ManyToOne(targetEntity="Tutto\Bundle\UtilBundle\Entity\Role", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     *
     * @var Role
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Tutto\Bundle\UtilBundle\Entity\Role", mappedBy="parent")
     *
     * @var Role[]
     */
    protected $children;

    public function __construct() {
        $this->children = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAlias() {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias) {
        $this->alias = $alias;
    }

    /**
     * @return Role
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param Role $parent
     */
    public function setParent(Role $parent) {
        $this->parent = $parent;
    }

    /**
     * @param Role $role
     */
    public function addChild(Role $role) {
        $role->setParent($this);
        $this->children[] = $role;
    }

    /**
     * @return Role[]
     */
    public function getChildren() {
        return $this->children;
    }
}