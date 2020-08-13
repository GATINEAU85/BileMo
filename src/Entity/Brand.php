<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Nelmio\ApiDocBundle\Annotation\Model as Mod;
use Swagger\Annotations as SWG;

/**
 * Brand
 *
 * @ORM\Table(name="brand")
 * @ORM\Entity
 */
class Brand
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"default", "id"})
     * @SWG\Property(type="integer", description="The unique identifier of the brand.")
     */
    private $id;
        
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\Length(max="255", maxMessage="The name is to long. 255 characters maximum")
     * @Assert\NotBlank
     * @Groups({"default"})
     * @SWG\Property(type="string", maxLength=255, description="The name of the brand.")
     */
    private $name;
    
//    /**
//     * @ORM\OneToMany(targetEntity="Model", mappedBy="brand", cascade={"persist"})
//     */
//    private $models;
    
//    public function __construct()
//    {
//        $this->models = new ArrayCollection();
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
//    
//    public function getModels()
//    {
//        return $this->models;
//    }
}