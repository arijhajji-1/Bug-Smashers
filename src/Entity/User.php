<?php

namespace App\Entity;
use App\Repository\UserRepository;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $lastName;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $adresse;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $photo;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 11,
     *      max = 11,
     *      minMessage = "Your telephone must be 11 numbers",
     *      maxMessage = "Your telephone must be 11 numbers"
     * )
     */
    private $telephone;
    /**
     * @ORM\Column(type="integer", length=8)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Your cin must be 8 numbers",
     *      maxMessage = "Your cin must be 8 numbers"
     * )
     */
    private $cin;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotBlank
     */
    private $date_naissance;
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Your password must be at least 8 numbers"
     * )
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity=Wishlist::class, mappedBy="users", cascade={"persist", "remove"})
     */
    private $wishlist;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
       // $roles[] = 'ROLE_USER';

        return $roles;
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

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


    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }



    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * @param mixed $cin
     */
    public function setCin($cin): void
    {
        $this->cin = $cin;
    }

    public function getWishlist(): ?Wishlist
    {
        return $this->wishlist;
    }

    public function setWishlist(?Wishlist $wishlist): self
    {
        // unset the owning side of the relation if necessary
        if ($wishlist === null && $this->wishlist !== null) {
            $this->wishlist->setUsers(null);
        }

        // set the owning side of the relation if necessary
        if ($wishlist !== null && $wishlist->getUsers() !== $this) {
            $wishlist->setUsers($this);
        }

        $this->wishlist = $wishlist;

        return $this;
    }


}
