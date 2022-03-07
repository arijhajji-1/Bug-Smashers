<?php

namespace App\Entity;

use App\Repository\AvisReparationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=AvisReparationRepository::class)
 */
class AvisReparation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  * @Assert\NotBlank(message="This field must be filled")
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=50)
     *  * @Assert\NotBlank(message="This field must be filled")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @Assert\NotBlank(message="This field must be filled")
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity=Reparation::class, inversedBy="avisReparation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $idrep;

    /**
     * @ORM\Column(type="integer")
     */
    private $iduser;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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



    public function getIdrep(): ?Reparation
    {
        return $this->idrep;
    }

    public function setIdrep(Reparation $idrep): self
    {
        $this->idrep = $idrep;

        return $this;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }
}
