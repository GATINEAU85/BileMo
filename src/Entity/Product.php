<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Product
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $model;

    /**
     * @ORM\Column(type="text")
     */
    private $description;
    
    /**
     * @ORM\Column(type="string")
     */
    private $brand;
        
    /**
     * @ORM\Column(type="string")
     */
    private $color;
        
    /**
     * @ORM\Column(type="integer")
     */
    private $memoryStorage;
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function getDescription()
    {
        return $this->content;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
    
    public function getBrand()
    {
        return $this->content;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }
    
    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }
    
    public function getMemoryStockage()
    {
        return $this->memoryStorage;
    }

    public function setMemoryStockage($memoryStorage)
    {
        $this->memoryStorage = $memoryStorage;

        return $this;
    }
}