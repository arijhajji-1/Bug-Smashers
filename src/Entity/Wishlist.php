<?php

namespace App\Entity;

use App\Repository\WishlistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WishlistRepository::class)
 */
class Wishlist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=ProduitAcheter::class, inversedBy="wishlists")
     */
    private $produitAcheter;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="wishlist", cascade={"persist", "remove"})
     */
    private $users;

    public function __construct()
    {
        $this->produitAcheter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        }

        return $this;
    }

    public function removeProduitAcheter(ProduitAcheter $produitAcheter): self
    {
        $this->produitAcheter->removeElement($produitAcheter);

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }
}
