<?php

namespace App\Entity;

use App\Repository\AvisProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AvisProduitRepository::class)
 */
class Avis_Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez donner une description !")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=ProduitAcheter::class, inversedBy="avis")
     */
    private $produitAcheter;

    /**
     * @ORM\ManyToOne(targetEntity=ProduitLouer::class, inversedBy="avis")
     */
    private $produitLouer;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank(message="Vous devez choisir au moin 1 star !")
     */
    private $rating;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProduitAcheter(): ?ProduitAcheter
    {
        return $this->produitAcheter;
    }

    public function setProduitAcheter(?ProduitAcheter $produitAcheter): self
    {
        $this->produitAcheter = $produitAcheter;

        return $this;
    }

    public function getProduitLouer(): ?ProduitLouer
    {
        return $this->produitLouer;
    }

    public function setProduitLouer(?ProduitLouer $produitLouer): self
    {
        $this->produitLouer = $produitLouer;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
