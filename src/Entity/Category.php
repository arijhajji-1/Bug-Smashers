<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("category")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Le nom du categorie doit etre non-vide")
     * @Groups ("category")
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=ProduitAcheter::class, mappedBy="category", orphanRemoval=true)
     */
    private $produitsAcheter;
    /**
     * @ORM\OneToMany(targetEntity=ProduitLouer::class, mappedBy="category")
     */
    private $produitsLouer;


    public function __construct()
    {
        $this->produitsAcheter = new ArrayCollection();
        $this->produitsLouer = new ArrayCollection();
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

    /**
     * @return Collection|ProduitsAcheter[]
     */
    public function getProduitAcheter(): Collection
    {
        return $this->produitsAcheter;
    }

    public function addProduitAcheter(ProduitAcheter $produitsAcheter): self
    {
        if (!$this->produitsAcheter->contains($produitsAcheter)) {
            $this->produitsAcheter[] = $produitsAcheter;
            $produitsAcheter->setCategory($this);
        }

        return $this;
    }

    public function removeProduitAcheter(ProduitAcheter $produitsAcheter): self
    {

        if ($this->produitsAcheter->removeElement($produitsAcheter)) {
            // set the owning side to null (unless already changed)
            if ($produitsAcheter->getCategory() === $this) {
                $produitsAcheter->setCategory(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|ProduitsLouer[]
     */
    public function getProduitLouer(): Collection
    {
        return $this->produitsLouer;
    }

    public function addProduitLouer(ProduitLouer $produitsLouer): self
    {
        if (!$this->produitsLouer->contains($produitsLouer)) {
            $this->produitsLouer[] = $produitsLouer;
            $produitsLouer->setCategory($this);
        }

        return $this;
    }

    public function removeProduitsLouer(ProduitLouer $produitsLouer): self
    {
        if ($this->produitsLouer->removeElement($produitsLouer)) {
            // set the owning side to null (unless already changed)
            if ($produitsLouer->getCategory() === $this) {
                $produitsLouer->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->label;
    }
}
