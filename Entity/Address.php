<?php

namespace Tutto\Bundle\UtilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Address
 * @package Tutto\Bundle\UtilBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="addresses")
 */
class Address {
    const TYPE_HOME = 1;
    const TYPE_FLAT = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $country;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $city;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $province;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $postCode;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $street;

    /**
     * @ORM\Column(type="smallint", nullable=false, options={"default": 1})
     *
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(length=45, nullable=true)
     *
     * @var string
     */
    protected $flatNumber;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $blockNumber;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $homeNumber;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country) {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getProvince() {
        return $this->province;
    }

    /**
     * @param string $province
     */
    public function setProvince($province) {
        $this->province = $province;
    }

    /**
     * @return string
     */
    public function getPostCode() {
        return $this->postCode;
    }

    /**
     * @param string $postCode
     */
    public function setPostCode($postCode) {
        $this->postCode = $postCode;
    }

    /**
     * @return string
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street) {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getFlatNumber() {
        return $this->flatNumber;
    }

    /**
     * @param string $flatNumber
     */
    public function setFlatNumber($flatNumber) {
        $this->flatNumber = $flatNumber;
    }

    /**
     * @return string
     */
    public function getBlockNumber() {
        return $this->blockNumber;
    }

    /**
     * @param string $blockNumber
     */
    public function setBlockNumber($blockNumber) {
        $this->blockNumber = $blockNumber;
    }

    /**
     * @return string
     */
    public function getHomeNumber() {
        return $this->homeNumber;
    }

    /**
     * @param string $homeNumber
     */
    public function setHomeNumber($homeNumber) {
        $this->homeNumber = $homeNumber;
    }
}