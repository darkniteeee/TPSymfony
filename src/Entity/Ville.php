<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 * @ORM\Table(name="villes")
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id_ville", type="integer", options={"unsigned": true})
     */
    private $id_ville;

    /**
     * @ORM\Column(name="nom_ville", type="string", length=30)
     * @Assert\NotBlank(message="Le nom de la ville est requis !")
     * @Assert\Length(
     *     min = 2,
     *     max = 30,
     *     minMessage = "Le  nom de la ville doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le  nom de la ville doit contenir au maximum {{ limit }} caractères !"
     * )
     */
    private $nom_ville;
    //TODO si 2 villes avec même nom faire recherche avec code postal

    /**
     * @ORM\Column(name="code_postal", type="string", length=10)
     * @Assert\NotBlank(message="Le code postal de la ville est requis !")
     * @Assert\Length(
     *     min = 5,
     *     max = 10,
     *     minMessage = "Le code postal doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le code postal doit contenir au maximum {{ limit }} caractères !"
     * )
     *
     */
    private $codePostal;

    public function getIdVille(): ?int
    {
        return $this->id_ville;
    }

    public function getNomVille(): ?string
    {
        return $this->nom_ville;
    }

    public function setNomVille(string $nom_ville): void
    {
        $this->nom_ville = $nom_ville;

    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): void
    {
        $this->codePostal = $codePostal;

    }
}
