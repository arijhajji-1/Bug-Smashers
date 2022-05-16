<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Commande;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank(message="Description is required")
     * @Groups("post:read")
     */
    private $description;



    /**
     *   @Assert\NotBlank(message = "La date de début doit être saisie.")
     * @Assert\Date(message = "La date de début n'est pas valide.")
     * @ORM\Column(type="date")
     * @Assert\GreaterThan("today")
     * @Groups("post:read")
     */
    private $date;

    /**

     * @ORM\Column(type="boolean",nullable="yes")

     */
    private $statut;







    /**
     * @ORM\Column(type="string", length=50)
     * @Groups("post:read")
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="topic is required")
     * @Groups("post:read")
     */
    private $sujet;

    /**
     * @ORM\OneToOne(targetEntity=Commande::class, inversedBy="reclamation", cascade={"persist", "remove"})
     */
    private $idCommande;





    public function __construct()
    {
        $this->date = new \Datetime;
    }

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


    public function registerBundles()
    {
        return array(
            // ...
            new MercurySeries\FlashyBundle\MercurySeriesFlashyBundle(),
            new Ob\HighchartsBundle\ObHighchartsBundle(),
            // ...
        );
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





    /* public function __construct()
     {
         $this->date = new \Datetime;

     }*/



    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getCategorie();
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

    public function getIdCommande(): ?Commande
    {
        return $this->idCommande;
    }

    public function setIdCommande(?Commande $idCommande): self
    {
        $this->idCommande = $idCommande;

        return $this;
    }





}
