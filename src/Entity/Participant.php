<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
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
     * @ORM\Column(name="id_participant", type="integer", options={"unsigned": true})
     */
    private ?int $id_participant = null;

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

    #Mise en place en des getters setters

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }
}