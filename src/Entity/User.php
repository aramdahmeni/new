<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[Entity]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'discr', type: 'string')]
#[DiscriminatorMap([
    'user' => User::class,
    'administrateur' => Administrateur::class,
    'enseignant' => Enseignant::class, // Assuming Enseignant class exists
    'etudiant' => Etudiant::class,   // Assuming Etudiant class exists
])]
class User implements UserInterface {
   
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $numtel = null;

    #[ORM\Column]
    private ?int $type = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): static
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }




















    
      /**
         * A visual identifier that represents this user.
         *
         * @see UserInterface
         */
        public function getUsername(): string
        {
                return (string) $this->username;
        }

        public function setUsername(string $username): self
        {
                $this->username = $username;

                return $this;
        }


    /**
         * @see UserInterface
         */
        public function getRoles(): array
        {
                $roles = $this->roles;
                $roles[] = 'ROLE_USER';

                return array_unique($roles);
        }

        public function setRoles(array $roles): self
        {
                $this->roles = $roles;

                return $this;
        }


        /**
         * @see UserInterface
         */
        public function getSalt()
        {
        }

        /**
         * @see UserInterface
         */
        public function eraseCredentials()
        {
        }
}
