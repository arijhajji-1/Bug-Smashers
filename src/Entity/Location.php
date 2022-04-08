<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("location")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThan("today UTC")
     * @Groups ("location")
     */
    private $dateDEB;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThan(propertyPath="dateDEB")
     * @Groups ("location")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="empty field")
     * @Groups ("location")
     */
    private $TotalL;

    /**
     * @ORM\OneToMany(targetEntity=ProduitLouer::class, mappedBy="location")
     * @Groups ("location")
     */
    private $produit;

    /**
     * @ORM\Column(type="integer")
     */
    private $iduser;



    public function __construct()
    {
        $this->produit = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDEB(): ?\DateTimeInterface
    {
        return $this->dateDEB;
    }

    public function setDateDEB(\DateTimeInterface $dateDEB): self
    {
        $this->dateDEB = $dateDEB;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getTotalL(): ?float
    {
        return $this->TotalL;
    }

    public function setTotalL(float $TotalL): self
    {
        $this->TotalL = $TotalL;

        return $this;
    }

    /**
     * @return Collection|produitLouer[]
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(produitLouer $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit[] = $produit;
            $produit->setLocation($this);
        }

        return $this;
    }

    public function removeProduit(produitLouer $produit): self
    {
        if ($this->produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getLocation() === $this) {
                $produit->setLocation(null);
            }
        }

        return $this;
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
