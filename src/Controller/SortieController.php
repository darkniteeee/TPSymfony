<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;

/**
 * @Route(name="sortie_", path="sortie/")
 */
class SortieController extends AbstractController
{
    /**
     * @Route(name="list", path="list")
     */
    public function list(EntityManagerInterface  $entityManager){

        $sorties = $entityManager->getRepository('App:Sortie');

        return $this->render('sortie/list.html.twig', [
            'sorties' => $sorties,
        ]);

    }


}