<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RTG\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Association
{

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $addressCountry;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $addressLocality;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $addressPostalCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $addressStreet;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $nameCanonical;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $website;
    
    public function __construct($addressCountry, $addressLocality, $addressPostalCode, $addressStreet, $description, $email, $name, $website) {
        $this->addressCountry = $addressCountry;
        $this->addressLocality = $addressLocality;
        $this->addressPostalCode = $addressPostalCode;
        $this->addressStreet = $addressStreet;
        $this->description = $description;
        $this->email = $email;
        $this->setName($name);
        $this->website = $website;
    }

    
    public function getAddressCountry() {
        return $this->addressCountry;
    }

    public function getAddressLocality() {
        return $this->addressLocality;
    }

    public function getAddressPostalCode() {
        return $this->addressPostalCode;
    }

    public function getAddressStreet() {
        return $this->addressStreet;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getNameCanonical() {
        return $this->nameCanonical;
    }

    public function getWebsite() {
        return $this->website;
    }

    public function setAddressCountry($addressCountry) {
        $this->addressCountry = $addressCountry;
        return $this;
    }

    public function setAddressLocality($addressLocality) {
        $this->addressLocality = $addressLocality;
        return $this;
    }

    public function setAddressPostalCode($addressPostalCode) {
        $this->addressPostalCode = $addressPostalCode;
        return $this;
    }

    public function setAddressStreet($addressStreet) {
        $this->addressStreet = $addressStreet;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        $name_canonical = preg_replace('/\s+/', '', strtolower($name));
        $this->setNameCanonical($name_canonical);
        return $this;
    }

    public function setNameCanonical($nameCanonical) {
        $this->nameCanonical = $nameCanonical;
        return $this;
    }

    public function setWebsite($website) {
        $this->website = $website;
        return $this;
    }


    
    

}
