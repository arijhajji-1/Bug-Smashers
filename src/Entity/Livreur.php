<?php

namespace App\Entity;

use App\Repository\LivreurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Egulias\EmailValidator\Validation\EmailValidation;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivreurRepository::class)
 */
class Livreur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="entrer le nom du livreur")
     * @Groups("post:read")
     */
    private $nomLivreur;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="entrer le prénom du livreur ")
     * @Groups("post:read")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="saisir une adresse")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="entrer votre adresse mail")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @Groups("post:read")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="cin is required")

     * @Assert\Length(
     *       min = 10,
     *      max = 10,
     * )
     */
    private $cin;

    /**
     * @ORM\OneToMany(targetEntity=Livraison::class, cascade={"persist", "remove"}, mappedBy="livreur")
     */
    private $livraison;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups("post:read")
     */
    private $statut;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLivreur(): ?string
    {
        return $this->nomLivreur;
    }

    public function setNomLivreur(string $nomLivreur): self
    {
        $this->nomLivreur = $nomLivreur;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * @return Collection|Livraison[]
     */
    public function getLivraison(): Collection
    {
        return $this->livraison;
    }

    public function addLivraison(Livraison $livraison): self
    {
        if (!$this->livraison->contains($livraison)) {
            $this->livraison[] = $livraison;
            $livraison->addLivreur($this);
        }

        return $this;
    }

    public function removeLivraison(Livraison $livraison): self
    {
        if ($this->livraison->removeElement($livraison)) {
            $livraison->removeLivreur($this);
        }

        return $this;
    }
    public function addlivraizon(Livraison $livraison)
    {
        $this->livraison->add($livraison);
      //  $livraison->setQuestion($this);
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
    public function __toString()
    {
        return  $this -> getNomLivreur(). ' ' . $this -> getPrenom() ;
    }
}
