<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository", repositoryClass=ParticipantRepository::class)
 * @ORM\Table(name="participants")
 * @UniqueEntity(fields={"pseudo"}, message="Le pseudo est déjà utilisé !")
 * @UniqueEntity(fields={"email"}, message="L'adresse e-mail est déjà utilisée !")
 */
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", options={"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="nom", type="string", length=30)
     * @Assert\NotBlank(message="Le nom est requis !")
     * @Assert\Length(
     *     min = 1,
     *     max = 30,
     *     minMessage = "Le nom doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le nom doit contenir au maximum {{ limit }} caractères !"
     * )
     */
    private ?string $nom = null;

    /**
     * @ORM\Column(name="prenom", type="string", length=30)
     * @Assert\NotBlank(message="Le prénom est requis !")
     * @Assert\Length(
     *     min = 1,
     *     max = 30,
     *     minMessage = "Le prénom doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le prénom doit contenir au maximum {{ limit }} caractères !"
     * )
     */
    private ?string $prenom = null;

    /**
     * @ORM\Column(name="telephone", type="string", length=15, nullable=true)
     *  @Assert\Length(
     *     min = 10,
     *     max = 15,
     *     minMessage = "Le telephone doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le telephone doit contenir au maximum {{ limit }} caractères !"
     * )
     */
    private ?string $telephone = null;

    /**
     * @ORM\Column(name="mail", type="string", length=80, unique=true)
     * @Assert\NotBlank(message="Le mail est requis !")
     * @Assert\Length(
     *     min = 5,
     *     max = 80,
     *     minMessage = "Le mail doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le mail doit contenir au maximum {{ limit }} caractères !"
     * )
     */
    private ?string $mail = null;

    /**
     * @ORM\Column(name="pseudo", type="string", length=30, unique=true)
     * @Assert\NotBlank(message="Le pseudo est requis !")
     * @Assert\Length(
     *     min = 2,
     *     max = 30,
     *     minMessage = "Le pseudo doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le pseudo doit contenir au maximum {{ limit }} caractères !"
     * )
     */
    private ?string $pseudo = null;

    /**
     * @ORM\Column(name="password", type="string", length=100)
     */
    private string $password;

    /**
     * @Assert\NotBlank(message="Le mot de passe est requis !", groups={"registration"})
     * @Assert\Length(
     *     min = 8,
     *     max = 50,
     *     minMessage = "Le mot de passe doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le mot de passe doit contenir au maximum {{ limit }} caractères !",
     *     groups={"registration"})
     * )
     * @Assert\NotCompromisedPassword(message="Le mot de passe n'est pas assez complexe !", skipOnError=true, groups={"registration"})
     */
    private ?string $plainPassword = null;

    /**
     * @ORM\Column(name="photo_profil", type="string", length=250, nullable=true)
     */
    private ?string $photo_profil = null;

    /**
     * @ORM\Column(name="administrateur", type="boolean")
     */
    private ?Boolean $administrateur = null;

    /**
     * @ORM\Column(name="actif", type="boolean")
     */
    private ?Boolean $actif = null;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, inversedBy="participants")
     */
    private $inscriptions;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur")
     */
    private $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    private array $roles = [];

    // ********************************* Déclaration des getters Setters **********
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->pseudo;
    }
    /*
     * Méthode déprécié avec ce symfony du coup on utilise getUserIdentifier
     */
    public function getUsername() : string
    {
        //TODO ajouter l'email après
        return $this-> getUserIdentifier();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string|null $prenom
     */
    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string|null $telephone
     */
    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return string|null
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param string|null $mail
     */
    public function setMail(?string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return string|null
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * @param string|null $pseudo
     */
    public function setPseudo(?string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }


    /**
     * @return string|null
     */
    public function getPhotoProfil(): ?string
    {
        return $this->photo_profil;
    }

    /**
     * @param string|null $photo_profil
     */
    public function setPhotoProfil(?string $photo_profil): void
    {
        $this->photo_profil = $photo_profil;
    }

    /**
     * @return bool|null
     */
    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    /**
     * @param bool|null $administrateur
     */
    public function setAdministrateur(?bool $administrateur): void
    {
        $this->administrateur = $administrateur;
    }

    /**
     * @return bool|null
     */
    public function getActif(): ?bool
    {
        return $this->actif;
    }

    /**
     * @param bool|null $actif
     */
    public function setActif(?bool $actif): void
    {
        $this->actif = $actif;
    }



    // ******************* Méthodes pour Role ****************
    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string $role
     * @return void
     */
    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    /**
     * @param string $role
     * @return void
     */
    public function removeRole(string $role): void
    {
        $this->roles = array_filter($this->roles, function (string $currentRole) use ($role) {
            return $currentRole !== $role;
        });
    }
    // ******************* Méthodes pour password ****************
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
    //**************************** Declaration des Méthodes ***********************

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->organisateur = new ArrayCollection();
    }

    public function eraseCredentials()
    {

    }

    public function __call($name, $arguments)
    {

    }

    // ************** Méthodes liées à inscription ****************
    /**
     * @return Collection<int, Sortie>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Sortie $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
        }

        return $this;
    }

    public function removeInscription(Sortie $inscription): self
    {
        $this->inscriptions->removeElement($inscription);

        return $this;
    }

    // ************** Méthodes liées à Organisateur ****************
    /**
     * @return Collection<int, Sortie>
     */
    public function getOrganisateur(): Collection
    {
        return $this->organisateur;
    }

    public function addOrganisateur(Sortie $organisateur): self
    {
        if (!$this->organisateur->contains($organisateur)) {
            $this->organisateur[] = $organisateur;
            $organisateur->setOrganisateur($this);
        }

        return $this;
    }

    public function removeOrganisateur(Sortie $organisateur): self
    {
        if ($this->organisateur->removeElement($organisateur)) {
            // set the owning side to null (unless already changed)
            if ($organisateur->getOrganisateur() === $this) {
                $organisateur->setOrganisateur(null);
            }
        }

        return $this;
    }

    // ************** Méthodes liées à site ****************
    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }
}