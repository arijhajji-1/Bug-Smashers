<?php

namespace App\Entity;

use App\Entity\Commande;
use App\Entity\Livreur;
use App\Repository\LivraisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use MercurySeries\FlashyBundle\MercurySeriesFlashyBundle;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use MercurySeries\FlashyBundle\FlashyNotifier;

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

    /**
     * @ORM\OneToOne(targetEntity=Commande::class, inversedBy="livraison", cascade={"persist", "remove"})
     */
    private $commande;






    /*  public function __construct()
      {
          $this->livraison = new ArrayCollection();
          $this->livreur = new ArrayCollection();
      }
  */

    public function registerBundles()
    {
        return array(
            // ...
            new MercurySeries\FlashyBundle\MercurySeriesFlashyBundle(),
            // ...
        );
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }





}
