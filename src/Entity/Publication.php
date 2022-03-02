<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=PublicationRepository::class)
 */
class Publication
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
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Your title cannot contains digit"
     * )
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Your description must be at least 8 caracters"
     * )
     */
    private $desription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="publications")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true)
     */
    private $users;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaire", mappedBy="publications" , cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="commentaire", referencedColumnName="id", nullable=true)
     */
    private $commentaires;
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

    public function getDesription(): ?string
    {
        return $this->desription;
    }

    public function setDesription(string $desription): self
    {
        $this->desription = $desription;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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
    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }
    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }
}
