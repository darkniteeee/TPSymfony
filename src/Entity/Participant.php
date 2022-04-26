<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository", repositoryClass=ParticipantRepository::class)
 */
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
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
    private string $password;

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