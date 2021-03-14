<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"username"},
 *     message="{{ value }} est déjà utilisé."
 * )
 * 
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap(
 *      typeProperty="type",
 *      mapping={"user" = "User", "admin" = "Admin", "reader" = "Reader"}
 * )
 */
abstract class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 6,
     *      minMessage = "Le champ username doit posséder {{ limit }} caractères.",
     * )
     */
    protected $username;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$/",
     *      message="Le mot de passe n'est pas bon format"
     * )
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Email
     */
    protected $email;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $connectedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime;
    }
    
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }
    
    /**
     * @param  string $username
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    
    /**
     * @param  array $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }
    
    /**
     * @param  string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    
    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * @param  string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    
    /**
     * @param  DateTimeInterface $createdAt
     * @return self
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    
    /**
     * @return DateTimeInterface|null
     */
    public function getConnectedAt(): ?\DateTimeInterface
    {
        return $this->connectedAt;
    }
    
    /**
     * setConnectedAt
     *
     * @param  DateTimeInterface|null $connectedAt
     * @return self
     */
    public function setConnectedAt(?\DateTimeInterface $connectedAt): self
    {
        $this->connectedAt = $connectedAt;

        return $this;
    }
}
