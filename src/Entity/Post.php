<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idPost = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\OneToMany(targetEntity: pieceJointe::class, mappedBy: 'idPost')]
    private Collection $relation;

    public function __construct()
    {
        $this->relation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * @return Collection<int, pieceJointe>
     */
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(pieceJointe $relation): static
    {
        if (!$this->relation->contains($relation)) {
            $this->relation->add($relation);
            $relation->setIdPost($this);
        }

        return $this;
    }

    public function removeRelation(pieceJointe $relation): static
    {
        if ($this->relation->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getIdPost() === $this) {
                $relation->setIdPost(null);
            }
        }

        return $this;
    }
}
