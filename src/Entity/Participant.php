<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 * @ORM\Table(name="participants")
 * @UniqueEntity(fields={"pseudo"}, message="Le pseudo est déjà utilisé !")
 * @UniqueEntity(fields={"email"}, message="L'adresse e-mail est déjà utilisée !")
 */
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue (strategy="AUTO")
     * @ORM\Column(name="id", type="integer", options={"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="email", type="string", length=80, unique=true)
     * @Assert\NotBlank(message="Le mail est requis !")
     * @Assert\Length(
     *     min = 5,
     *     max = 80,
     *     minMessage = "L'email doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "L'email doit contenir au maximum {{ limit }} caractères !"
     * )
     */
    private ?string $email;

    /**
     * @ORM\Column(name="roles", type="json", nullable=true)
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(name="password", type="string", length=180)
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

    private ?string $newPassword = null;

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
    private ?string $nom =null;

    /**
     *  * @ORM\Column(name="prenom", type="string", length=30)
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
     * @Assert\Length(
     *     min = 10,
     *     max = 15,
     *     minMessage = "Le telephone doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le telephone doit contenir au maximum {{ limit }} caractères !"
     * )
     */
    private ?string $telephone =null;


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
     * @ORM\Column(name="photo_profil", type="string", length=250, nullable=true)
     */
    private ?string $photo_profil = null;

    /**
     * @ORM\Column(name="administrateur", type="boolean")
     */
    private bool $administrateur;

    /**
     * @ORM\Column(name="actif", type="boolean")
     */
    private bool $actif;


    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur", orphanRemoval=true)
     */
    private $organise;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, inversedBy="inscrits")
     */
    private $inscriptions;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="participants")
     */
    private $site_id;

       //************************* DECLARATION GETTERS SETTERS ********
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
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

    public function getSiteId(): ?site
    {
        return $this->site_id;
    }

    public function setSiteId(?site $site_id): self
    {
        $this->site_id = $site_id;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getInscriptions(): ArrayCollection
    {
        return $this->inscriptions;
    }

    /**
     * @param ArrayCollection $inscriptions
     */
    public function setInscriptions(ArrayCollection $inscriptions): void
    {
        $this->inscriptions = $inscriptions;
    }
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
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

    // ******************* Méthodes pour Role ****************

    /**
     * @see UserInterface
     */
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

    //************************* DECLARATION DES METHODES ********

    public function __construct()
    {
        $this->organise = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
        $this->administrateur = false;
        $this->actif = false;

    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }


    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     * return null car le hachage sera gérer indépendament par bcrypt ou sodium
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getOrganise(): Collection
    {
        return $this->organise;
    }

    public function addOrganise(Sortie $organise): self
    {
        if (!$this->organise->contains($organise)) {
            $this->organise[] = $organise;
            $organise->setOrganisateur($this);
        }

        return $this;
    }

    public function removeOrganise(Sortie $organise): self
    {
        if ($this->organise->removeElement($organise)) {
            // set the owning side to null (unless already changed)
            if ($organise->getOrganisateur() === $this) {
                $organise->setOrganisateur(null);
            }
        }

        return $this;
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






}
