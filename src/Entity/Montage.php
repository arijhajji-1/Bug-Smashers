<?php

namespace App\Entity;

use App\Repository\MontageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MontageRepository::class)
 */
class Montage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="processeur is required")
     */
    private $processeur;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="carte graphique is required")
     */
    private $carte_graphique;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="carte mere is required")
     */
    private $carte_mere;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="disque systeme is required")
     */
    private $disque_systeme;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="boitier is required")
     */
    private $boitier;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="stockage supp is required")
     */
    private $stockage_supp;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\OneToMany(targetEntity=ProduitAcheter::class, mappedBy="montage")
     */
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProcesseur(): ?string
    {
        return $this->processeur;
    }

    public function setProcesseur(string $processeur): self
    {
        $this->processeur = $processeur;

        return $this;
    }

    public function getCarteGraphique(): ?string
    {
        return $this->carte_graphique;
    }

    public function setCarteGraphique(string $carte_graphique): self
    {
        $this->carte_graphique = $carte_graphique;

        return $this;
    }

    public function getCarteMere(): ?string
    {
        return $this->carte_mere;
    }

    public function setCarteMere(string $carte_mere): self
    {
        $this->carte_mere = $carte_mere;

        return $this;
    }

    public function getDisqueSysteme(): ?string
    {
        return $this->disque_systeme;
    }

    public function setDisqueSysteme(string $disque_systeme): self
    {
        $this->disque_systeme = $disque_systeme;

        return $this;
    }

    public function getboitier(): ?string
    {
        return $this->boitier;
    }

    public function setboitier(string $boitier): self
    {
        $this->boitier = $boitier;

        return $this;
    }

    public function getStockageSupp(): ?string
    {
        return $this->stockage_supp;
    }

    public function setStockageSupp(string $stockage_supp): self
    {
        $this->stockage_supp = $stockage_supp;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Product $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setMontage($this);
        }

        return $this;
    }

    public function removeProduit(Product $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getMontage() === $this) {
                $produit->setMontage(null);
            }
        }

        return $this;
    }

}
