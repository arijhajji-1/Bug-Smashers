<?php

namespace App\Entity;

use App\Repository\MontageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\ProduitAcheter;
/**
 * @ORM\Entity(repositoryClass=MontageRepository::class)
 */
class Montage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="processeur is required")
     * @Groups ("post:read")
     */
    private $processeur;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="carte graphique is required")
     * @Groups ("post:read")
     */
    private $carte_graphique;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="carte mere is required")
     * @Groups ("post:read")
     */
    private $carte_mere;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="disque systeme is required")
     * @Groups ("post:read")
     */
    private $disque_systeme;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="boitier is required")
     * @Groups ("post:read")
     */
    private $boitier;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="stockage supp is required")
     * @Groups ("post:read")
     */
    private $stockage_supp;

    /**
     * @ORM\Column(type="integer")
     * @Groups ("post:read")
     */
    private $montant;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $iduser;

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



    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
