<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $text;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commentaires")
     */
    private $users;
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
}
