<?php

namespace App\Entity;

use App\Repository\ReparationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReparationRepository::class)
 */
class Reparation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="This field must be filled")
     * @Groups ("post:read")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="This field must be filled")
     * @Groups ("post:read")
     */
    private $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\GreaterThan("today UTC")
     * @Assert\NotBlank(message="This field must be filled")
     * @Groups ("post:read")

     */
    private $Reserver;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="This field must be filled")
     *  @Groups ("post:read")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $etat;

    /**
     * @ORM\OneToOne(targetEntity=AvisReparation::class, mappedBy="idrep", cascade={"persist", "remove"},orphanRemoval=true)
     */
    private $avisReparation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string",length=20)
     */
    private $telephone;

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
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getReserver(): ?\DateTimeInterface
    {
        return $this->Reserver;
    }

    public function setReserver(?\DateTimeInterface $Reserver): self
    {
        $this->Reserver = $Reserver;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getAvisReparation(): ?AvisReparation
    {
        return $this->avisReparation;
    }

    public function setAvisReparation(AvisReparation $avisReparation): self
    {
        // set the owning side of the relation if necessary
        if ($avisReparation->getIdrep() !== $this) {
            $avisReparation->setIdrep($this);
        }

        $this->avisReparation = $avisReparation;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function __toString()
    {
        return $this->getType();
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
