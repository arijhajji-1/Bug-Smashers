<?php

namespace App\Entity;

use App\Repository\ReparationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReparationRepository::class)
 */
class Reparation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="This field must be filled")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="This field must be filled")
     */
    private $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\GreaterThan("today UTC")
     * @Assert\NotBlank(message="This field must be filled")

     */
    private $Reserver;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="This field must be filled")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $etat;




    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getReserver(): ?\DateTimeInterface
    {
        return $this->Reserver;
    }

    public function setReserver(?\DateTimeInterface $Reserver): self
    {
        $this->Reserver = $Reserver;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }







}
