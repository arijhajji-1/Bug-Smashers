<?php

namespace App\Entity;

use App\Repository\ProduitLouerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProduitLouerRepository::class)
 */
class ProduitLouer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("produit")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="le nom ne peut pas etre vide")
     * @Groups("produit")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'article doit avoir une description")
     * @Groups("produit")
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     * @Assert\NotBlank(message="le prix ne peut pas etre vide")
     * @Groups("produit")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="l'etat du produit ne peut pas etre vide")
     * @Groups("produit")
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("produit")
     */
    private $imagePath;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="L'article doit avoir une marque")
     * @Groups("produit")
     */
    private $marque;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="produitsLouer")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups("produit")
     */
    private $category;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("produit")
     */
    private $dispo;

    /**
     * @ORM\OneToMany(targetEntity=Avis_Produit::class, mappedBy="produitLouer",orphanRemoval=true)
     */
    private $avis;
    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="produit")
     * @Groups("produit")
     */
    private $location;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
    }

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
    public function getLocation(): ?Location
    {
        return $this->location;
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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

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

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDispo(): ?bool
    {
        return $this->dispo;
    }
    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }
    public function setDispo(bool $dispo): self
    {
        $this->dispo = $dispo;

        return $this;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setProduitLouer($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getProduitLouer() === $this) {
                $avi->setProduitLouer(null);
            }
        }

        return $this;
    }
}
