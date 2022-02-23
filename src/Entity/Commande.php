<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="il faut inserer votre nom")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="il faut inserer votre prenom ")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="il faut inserer la methode de paiment")
     */
    private $paiment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;



    /**
     * @ORM\OneToMany(targetEntity=LigneCommande::class, mappedBy="Commande")
     */
    private $LigneCommande;

    public function __construct()
    {

        $this->LigneCommande = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPaiment(): ?string
    {
        return $this->paiment;
    }

    public function setPaiment(string $paiment): self
    {
        $this->paiment = $paiment;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection|LigneCommande[]
     */
    public function getLigneCommandes(): Collection
    {
        return $this->LigneCommandes;
    }

    public function addLigneCommande(LigneCommande $LigneCommande): self
    {
        if (!$this->LigneCommandes->contains($LigneCommande)) {
            $this->LigneCommandes[] = $LigneCommande;
            $LigneCommande->setCommande($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $LigneCommande): self
    {
        if ($this->LigneCommandes->removeElement($LigneCommande)) {
            // set the owning side to null (unless already changed)
            if ($LigneCommande->getCommande() === $this) {
                $LigneCommande->setCommande(null);
            }
        }

        return $this;
    }
}
