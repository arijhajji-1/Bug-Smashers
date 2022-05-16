<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("commande")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="empty field")
     * @Groups ("commande")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="empty field")
     * @Groups ("commande")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="empty field")
     * @Groups ("commande")
     */
    private $paiment;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="empty field")
     * @Groups ("commande")
     */
    private $adresse;





    /**
     * @ORM\Column(type="integer")
     * @Groups ("commande")
     *
     */
    private $Telephone;

    /**
     * @ORM\OneToMany(targetEntity=Facture::class, mappedBy="commande")
     */
    private $Facture;

    /**
     * @ORM\Column(type="integer")
     */
    private $iduser;

    /**
     * @ORM\OneToOne(targetEntity=Livraison::class, mappedBy="commande", cascade={"persist", "remove"})
     */
    private $livraison;

    /**
     * @ORM\OneToOne(targetEntity=Reclamation::class, mappedBy="idCommande", cascade={"persist", "remove"})
     */
    private $reclamation;





    public function __construct()
    {


        $this->Facture = new ArrayCollection();
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



    public function getTelephone(): ?int
    {
        return $this->Telephone;
    }

    public function setTelephone(int $Telephone): self
    {
        $this->Telephone = $Telephone;

        return $this;
    }

    /**
     * @return Collection|Facture[]
     */
    public function getFacture(): Collection
    {
        return $this->Facture;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->Facture->contains($facture)) {
            $this->Facture[] = $facture;
            $facture->setCommande($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->Facture->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getCommande() === $this) {
                $facture->setCommande(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNom();
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

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        // unset the owning side of the relation if necessary
        if ($livraison === null && $this->livraison !== null) {
            $this->livraison->setCommande(null);
        }

        // set the owning side of the relation if necessary
        if ($livraison !== null && $livraison->getCommande() !== $this) {
            $livraison->setCommande($this);
        }

        $this->livraison = $livraison;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): self
    {
        // unset the owning side of the relation if necessary
        if ($reclamation === null && $this->reclamation !== null) {
            $this->reclamation->setIdCommande(null);
        }

        // set the owning side of the relation if necessary
        if ($reclamation !== null && $reclamation->getIdCommande() !== $this) {
            $reclamation->setIdCommande($this);
        }

        $this->reclamation = $reclamation;

        return $this;
    }





}
