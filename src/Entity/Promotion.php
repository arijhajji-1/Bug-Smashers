<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
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
    private $label;

    /**
     * @ORM\Column(type="integer")
     */
    private $pourcentage;

    /**
     * @ORM\OneToMany(targetEntity=ProduitAcheter::class, mappedBy="promotion")
     */
    private $produitAcheter;


    public function __construct()
    {
        $this->produitAcheter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPourcentage(): ?int
    {
        return $this->pourcentage;
    }

    public function setPourcentage(int $pourcentage): self
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    /**
     * @return Collection<int, ProduitAcheter>
     */
    public function getProduitAcheter(): Collection
    {
        return $this->produitAcheter;
    }

    public function addProduitAcheter(ProduitAcheter $produitAcheter): self
    {
        if (!$this->produitAcheter->contains($produitAcheter)) {
            $this->produitAcheter[] = $produitAcheter;
            $produitAcheter->setPromotion($this);
        }

        return $this;
    }

    public function removeProduitAcheter(ProduitAcheter $produitAcheter): self
    {
        if ($this->produitAcheter->removeElement($produitAcheter)) {
            // set the owning side to null (unless already changed)
            if ($produitAcheter->getPromotion() === $this) {
                $produitAcheter->setPromotion(null);
            }
        }

        return $this;
    }

}
