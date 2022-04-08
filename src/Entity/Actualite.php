<?php

namespace App\Entity;

use App\Repository\ActualiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ActualiteRepository::class)
 */
class Actualite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="veuillez entrer le titre de l'événement")
     * @Groups("post:read")
     */
    private $titre;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="veuillez entrer la date de l'événement")
     * @Assert\GreaterThan("today UTC")
     * @Groups("post:read")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="veuillez entrer la description de l'événement")
     * @Groups("post:read")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $imageName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }
}
