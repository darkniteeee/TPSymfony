<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LieuRepository", repositoryClass=LieuRepository::class)
 * @ORM\Table(name="Lieux")
 * @UniqueEntity(fields={"nom_lieu"}, message="Ce nom de lieu existe déjà")
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", options={"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message="Le nom du lieu est requis !")
     * @ORM\Column(name="nom_lieu", type="string", length=30)
     * @Assert\Length(
     *     min = 1,
     *     max = 30,
     *     minMessage = "Le nom du lieu doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le nom du lieu doit contenir au maximum {{ limit }} caractères !"
     * )
     */
    private ?string $nom_lieu = null;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *     min = 1,
     *     max = 30,
     *     minMessage = "Le nom de la rue doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le nom de la rue doit contenir au maximum {{ limit }} caractères !"
     * )
     */
    private ?string $rue = null;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="lieux")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;


#Mise en place des getters setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLieu(): ?string
    {
        return $this->nom_lieu;
    }

    public function setNomLieu(string $nom_lieu): void
    {
        $this->nom_lieu= $nom_lieu;
    }


    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): void
    {
        $this->rue = $rue;

    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }
}
