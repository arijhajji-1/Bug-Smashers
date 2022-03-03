<?php

namespace App\Entity;

use App\Entity\Commande;
use App\Entity\Livreur;
use App\Repository\LivraisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivraisonRepository::class)
 */
class Livraison
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\OneToOne(targetEntity=Commande::class, mappedBy="livraison", cascade={"persist", "remove"})
     */
    private $commande;

    /**
     * @ORM\ManyToOne (targetEntity=Livreur::class, inversedBy="livraison")
     */
    public  $livreur;



    /**
     * @ORM\Column(type="string" , length=50 ,nullable="yes")
     * @Groups("post:read")
     */
    private $modpaie;

    /**
     * @ORM\Column(type="string" , length=50 ,nullable="yes")
     * @Groups("post:read")
     */
    private $modlivr;

    /**
     * @ORM\Column(type="string" , length=50 ,nullable="yes")
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255 ,nullable="yes")
     * @Groups("post:read")
     */
    private $description;

    /**
     * @ORM\Column(type="date" ,nullable="yes")
     * @Groups("post:read")
     */
    private $date;


  /*  public function __construct()
    {
        $this->livraison = new ArrayCollection();
        $this->livreur = new ArrayCollection();
    }
*/

    public function getId(): ?int
    {
        return $this->id;
    }





    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        // set the owning side of the relation if necessary
        if ($commande->getLivraison() !== $this) {
            $commande->setLivraison($this);
        }

        $this->commande = $commande;

        return $this;
    }

    /**
     * @return Collection|Livreur[]
     */
    public function getLivreur()//: Collection
    {
        return $this->livreur;
    }

   public function addLivreur(Livreur $livreur): self
    {
        if (!$this->livreur->contains($livreur)) {
            $this->livreur[] = $livreur;
        }

        return $this->livreur;

    }

    public function removeLivreur(Livreur $livreur): self
    {
        $this->livreur->removeElement($livreur);

        return $this->livreur;
    }



    public function getModpaie(): ?string
    {
        return $this->modpaie;
    }

    public function setModpaie(string $modpaie): self
    {
        $this->modpaie = $modpaie;

        return $this;
    }

    public function getModlivr(): ?string
    {
        return $this->modlivr;
    }

    public function setModlivr(string $modlivr): self
    {
        $this->modlivr = $modlivr;

        return $this;
    }

    public function getRegion(): ?bool
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function __toString()
    {
        return $this->commande;
    }


}
