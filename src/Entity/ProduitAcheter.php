<?php

namespace App\Entity;

use App\Repository\ProduitAcheterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProduitAcheterRepository::class)
 */
class ProduitAcheter
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
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="la quantité du produit ne peut pas etre vide")
     * @Groups("produit")
     */
    private $qte;

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
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="produitsAcheter")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups("produit")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Avis_Produit::class, mappedBy="produitAcheter",orphanRemoval=true)
     */
    private $avis;
    /**
     * @ORM\ManyToOne(targetEntity=Montage::class, inversedBy="produits")
     */
    private $montage;

    /**
     * @ORM\ManyToMany(targetEntity=Wishlist::class, mappedBy="produitAcheter")
     */
    private $wishlists;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="produitAcheter")
     */
    private $promotion;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->wishlists = new ArrayCollection();
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

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

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
            $avi->setProduitAcheter($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getProduitAcheter() === $this) {
                $avi->setProduitAcheter(null);
            }
        }

        return $this;
    }

    public function getMontage(): ?Montage
    {
        return $this->montage;
    }

    public function setMontage(?Montage $montage): self
    {
        $this->montage = $montage;

        return $this;
    }
    public function __toString()
    {
        return $this->getNom();
    }

    /**
     * @return Collection<int, Wishlist>
     */
    public function getWishlists(): Collection
    {
        return $this->wishlists;
    }

    public function addWishlist(Wishlist $wishlist): self
    {
        if (!$this->wishlists->contains($wishlist)) {
            $this->wishlists[] = $wishlist;
            $wishlist->addProduitAcheter($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlist $wishlist): self
    {
        if ($this->wishlists->removeElement($wishlist)) {
            $wishlist->removeProduitAcheter($this);
        }

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }
}
