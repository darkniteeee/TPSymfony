<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LieuRepository", repositoryClass=LieuRepository::class)
 * @ORM\Table(name="Users")
 * @UniqueEntity(fields={"nom_lieu"}, message="Ce nom de lieu existe déjà")
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id_lieu", type="integer", options={"unsigned": true})
     */
    private ?int $id_lieu;

    /**
     * @Assert\NotBlank(message="Le nom du lieu est requis !")
     * @ORM\Column(name="nom_lieu", type="string", length=30)
     * @Assert\Length(
     *     min = 1,
     *     max = 30)
     */
    private ?string $nom_lieu = null;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *     min = 1,
     *     max = 30)
     */
    private ?string $rue = null;


#Mise en place des getters setters
    public function getId(): ?int
    {
        return $this->id_lieu;
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
}
