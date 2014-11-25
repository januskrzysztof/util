<?php

namespace Tutto\Bundle\UtilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Person
 * @package Tutto\Bundle\UtilBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="person")
 */
class Person {
    const TAX_PESEL = 1;
    const TAX_NIP   = 2;

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
    protected $firstname;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $middlename;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $lastname;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @var string
     */
    protected $birthday;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $birthdayPlace;

    /**
     * @ORM\Column(length=255, nullable=true)
     *
     * @var string
     */
    protected $taxNumber;

    /**
     * @ORM\Column(type="smallint", nullable=false, options={"default": 1})
     *
     * @var int
     */
    protected $taxType;

    /**
     * @ORM\ManyToOne(targetEntity="Tutto\Bundle\UtilBundle\Entity\Address", cascade={"all"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     *
     * @var Address
     */
    protected $address;

    /**
     * @ORM\ManyToOne(targetEntity="Tutto\Bundle\UtilBundle\Entity\Address", cascade={"all"})
     * @ORM\JoinColumn(name="correspondence_address_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     *
     * @var Address
     */
    protected $correspondenceAddress;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     *
     * @var bool
     */
    protected $sameAddress = false;

    /**
     * @return string
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getMiddlename() {
        return $this->middlename;
    }

    /**
     * @param string $middlename
     */
    public function setMiddlename($middlename) {
        $this->middlename = $middlename;
    }

    /**
     * @return string
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getBirthday() {
        return $this->birthday;
    }

    /**
     * @param string $birthday
     */
    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getBirthdayPlace() {
        return $this->birthdayPlace;
    }

    /**
     * @param string $birthdayPlace
     */
    public function setBirthdayPlace($birthdayPlace) {
        $this->birthdayPlace = $birthdayPlace;
    }

    /**
     * @return string
     */
    public function getTaxNumber() {
        return $this->taxNumber;
    }

    /**
     * @param string $taxNumber
     */
    public function setTaxNumber($taxNumber) {
        $this->taxNumber = $taxNumber;
    }

    /**
     * @return int
     */
    public function getTaxType() {
        return $this->taxType;
    }

    /**
     * @param int $taxType
     * @throws LogicException
     */
    public function setTaxType($taxType) {
        if (in_array($taxType, [self::TAX_NIP, self::TAX_PESEL])) {
            $this->taxType = $taxType;
        } else {
            throw new LogicException("Tax type: '{$taxType}' is not valid.");
        }
    }

    /**
     * @return Address
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address) {
        $this->address = $address;
    }

    /**
     * @return Address
     */
    public function getCorrespondenceAddress() {
        return $this->correspondenceAddress;
    }

    /**
     * @param Address $correspondenceAddress
     */
    public function setCorrespondenceAddress(Address $correspondenceAddress) {
        $this->correspondenceAddress = $correspondenceAddress;
    }

    /**
     * @return boolean
     */
    public function isSameAddress() {
        return $this->sameAddress;
    }

    /**
     * @param boolean $sameAddress
     */
    public function setSameAddress($sameAddress) {
        $this->sameAddress = (boolean) $sameAddress;
    }
}