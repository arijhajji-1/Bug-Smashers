<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\GreaterThan("today UTC")
     * @Groups ("post:read")
     */
    private $dateFact;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="empty field")
     * @Groups ("post:read")
     */
    private $remise;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="empty field")
     * @Groups ("post:read")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="Facture")
     * @ORM\JoinColumn(nullable=false)
     * @Groups ("post:read")
     */
    private $commande;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFact(): ?\DateTimeInterface
    {
        return $this->dateFact;
    }

    public function setDateFact(\DateTimeInterface $dateFact): self
    {
        $this->dateFact = $dateFact;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(float $remise): self
    {
        $this->remise = $remise;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

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
