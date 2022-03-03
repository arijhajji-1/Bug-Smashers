<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Commande;


/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank(message="Description is required")
     * @Groups("post:read")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups("post:read")
     */
    private $categorie;

    /**
     * @ORM\Column(type="date")
     * @Groups("post:read")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean",nullable="yes")
     *@Groups("post:read")
     */
    private $statut;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Commande", cascade={"persist"})
     * @ORM\Column(type="string", length=50)
     * @Groups("post:read")
     */
    private $idCommande;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="topic is required")
     * @Groups("post:read")
     */
    private $sujet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function setIdCommande(int $idCommande): self
    {
        $this->idCommande = $idCommande;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }
    public function __construct()
    {
        $this->date = new \Datetime;

    }

}
