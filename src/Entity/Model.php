<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Nelmio\ApiDocBundle\Annotation\Model as Mod;
use Swagger\Annotations as SWG;

/**
 * Model
 *
 * @ORM\Table(name="model")
 * @ORM\Entity
 */
class Model
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"default", "id"})
     * @SWG\Property(type="integer", description="The unique identifier of the model.")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\Length(max="255", minMessage="The name is to long. 255 characters maximum")
     * @Assert\NotBlank
     * @Groups({"default"})
     * @SWG\Property(type="string", maxLength=255, description="The name of the model.")
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="os", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Groups({"default"})
     * @SWG\Property(type="string", maxLength=255, description="The OS of the model.")
     */
    private $os;
    
    /**
     * @var date
     *
     * @ORM\Column(name="release_date", type="date", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Date
     * @Groups({"default"})
     * @SWG\Property(type="date", description="The release date of the model.")
     */
    private $releaseDate;        

    /**
     * @var \Brand
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand", cascade={"all"}, fetch="EAGER")
     * @Groups({"default"})
     * @SWG\Property(ref=@Mod(type=Brand::class), description="This is the brand of the smartphone. It has also a lots of characteristics.")
     */
    private $brand;
    
//    /**
//     * @ORM\OneToMany(targetEntity="Product", mappedBy="model", cascade={"persist"})
//     */
//    private $products;
    
//    public function __construct()
//    {
//        $this->products = new ArrayCollection();
//    }

    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }   
    
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }
    
    public function getOs()
    {
        return $this->os;
    }

    public function setOs($os)
    {
        $this->os = $os;

        return $this;
    }
       
    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;
    }
//    
//    public function getProducts()
//    {
//        return $this->products;
//    }
}