<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"default", "id"})
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ref", type="string", nullable=false, unique=true)
     * @Assert\NotBlank(message="This field can't be empty")
     * @Assert\Length(min="13", max="25", minMessage="The reference must contain more than 13 characters.", maxMessage="The reference must contain less than 25 characters")
     * @Groups({"default"})
     */
    private $ref;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Groups({"default"})
     */
    private $description;
        
    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", nullable=true)
     * @Groups({"default"})
     */
    private $color;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="memory_stockage", type="integer", nullable=true)
     * @Groups({"default"})
     */
    private $memoryStorage;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     * @Assert\NotBlank(message="This field can't be empty.")
     * @Assert\Range(min="0", max="1500")
     * @Assert\Type(type="integer", message="The value must be an integer")
     * @Groups({"default"})
     */
    private $price;
    
    /**
     * @var \Model
     * @ORM\ManyToOne(targetEntity="App\Entity\Model", inversedBy="id")
     * @Groups({"default"})
     */
    private $model;
    
    public function getId()
    {
        return $this->id;
    }

    public function getRef()
    {
        return $this->ref;
    }

    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

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
        
    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }
    
    public function getModel()
    {
        return $this->model;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
    }
    
}