<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\ORM\Mapping as ORM;


#[Entity]

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant extends User
{
    #[ORM\Column]
    private ?int $typeET = null;

    #[ORM\Column(length: 255)]
    private ?string $specialite = null;

    #[ORM\ManyToOne(inversedBy: 'etudiants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?classe $idClasse = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getTypeET(): ?int
    {
        return $this->typeET;
    }

    public function setTypeET(int $typeET): static
    {
        $this->typeET = $typeET;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getIdClasse(): ?classe
    {
        return $this->idClasse;
    }

    public function setIdClasse(?classe $idClasse): static
    {
        $this->idClasse = $idClasse;

        return $this;
    }
}
