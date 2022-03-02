<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Your commentaire must be at least 8 caracters"
     * )
     */
    private $text;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commentaires", cascade={"persist", "remove"})
     */
    private $users;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datecommentaire;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publication", inversedBy="commentaires")
     */
    private $publications;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getUsers(): ?User
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers(?User $users): self
    {
        $this->users = $users;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getPublications(): ?Publication
    {
        return $this->publications;
    }

    /**
     * @param mixed $publications
     */
    public function setPublications(?Publication $publications): self
    {
        $this->publications = $publications;
        return $this;
    }
    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->datecommentaire;
    }

    public function setDateCommentaire(\DateTimeInterface $datecommentaire): self
    {
        $this->datecommentaire = $datecommentaire;

        return $this;
    }
}
