<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Le nom est requis !")
     * @Assert\Length(
     *     min = 1,
     *     max = 30,
     *     minMessage = "Le nom doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le nom doit contenir au maximum {{ limit }} caractères !"
     * )
     *
     *
     */
    private ?string $nom;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private ?string $prenom;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private ?string $telephone;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private ?string $mail;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private ?string $pseudo;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private ?string $mdp;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private ?string $photo_profil;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?Boolean $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?Boolean $actif;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getPhotoProfil(): ?string
    {
        return $this->photo_profil;
    }

    public function setPhotoProfil(?string $photo_profil): self
    {
        $this->photo_profil = $photo_profil;

        return $this;
    }

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }
}
