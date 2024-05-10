<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[Entity]

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
class Enseignant extends User
{

    #[ORM\Column]
    private ?int $typeENS = null;

    #[ORM\Column(length: 255)]
    private ?string $codeENS = null;

    #[ORM\Column]
    private ?int $nbAnneeExp = null;

    #[ORM\Column(length: 255)]
    private ?string $matiere = null;

    #[ORM\ManyToMany(targetEntity: classe::class, inversedBy: 'enseignants')]
    private Collection $idClasses;

    public function __construct()
    {
        $this->idClasses = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeENS(): ?int
    {
        return $this->typeENS;
    }

    public function setTypeENS(int $typeENS): static
    {
        $this->typeENS = $typeENS;

        return $this;
    }

    public function getCodeENS(): ?string
    {
        return $this->codeENS;
    }

    public function setCodeENS(string $codeENS): static
    {
        $this->codeENS = $codeENS;

        return $this;
    }

    public function getNbAnneeExp(): ?int
    {
        return $this->nbAnneeExp;
    }

    public function setNbAnneeExp(int $nbAnneeExp): static
    {
        $this->nbAnneeExp = $nbAnneeExp;

        return $this;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): static
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * @return Collection<int, classe>
     */
    public function getIdClasses(): Collection
    {
        return $this->idClasses;
    }

    public function addIdClass(classe $idClass): static
    {
        if (!$this->idClasses->contains($idClass)) {
            $this->idClasses->add($idClass);
        }

        return $this;
    }

    public function removeIdClass(classe $idClass): static
    {
        $this->idClasses->removeElement($idClass);

        return $this;
    }
}
