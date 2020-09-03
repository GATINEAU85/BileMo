<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Nelmio\ApiDocBundle\Annotation\Model as Mod;
use Swagger\Annotations as SWG;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email", message="Email is already use")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer", nullable=false, unique=true)
     * @SWG\Property(type="integer", description="The id of the user.")
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", length=180, unique=true, nullable=false)
     * @Assert\Length(max="180", maxMessage="The email field is to long. 180 characters maximum")
     * @Assert\NotBlank)
     * @SWG\Property(type="string", maxLength=180, description="The name of the user.")
     */
    private $username;
    
    /**
     * @ORM\Column(name="email", type="string", length=180, unique=true, nullable=false)
     * @Assert\Length(max="180", maxMessage="The email field is to long. 180 characters maximum")
     * @Assert\NotBlank)
     * @SWG\Property(type="string", maxLength=180, description="The email of the user.")
     */
    private $email;

    /**
     * @ORM\Column(name="roles", type="json", nullable=false)
     * @Assert\NotBlank)
     * @SWG\Property(type="string", description="The roles of the user.")
     */
    private $roles = [];

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", nullable=false)
     * @Assert\Length(min="8", minMessage="Password to short")
     * @Assert\NotBlank
     * @SWG\Property(type="string", minLength=8, description="The password of the user. It must be 8 characters.")
     */
    private $password;

    /**
     * @var \Customer
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="id")
     * @SWG\Property(ref=@Mod(type=Customer::class))
     */
    private $customer;
    
    private $links;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
//    
//    public function getToken(): ?string
//    {
//        return $this->token;
//    }
//
//    public function setToken(string $token): self
//    {
//        $this->token = $token;
//
//        return $this;
//    }
    
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }
    
    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    
    public function getLinks(): ?array
    {
        return $this->links;
    }

    public function setLinks(array $links): self
    {
        $this->links = $links;

        return $this;
    }
}
