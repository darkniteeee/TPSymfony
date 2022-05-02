<?php

namespace App\Controller;

use App\Repository\EtatRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="accueil_")
 */
class AccueilController extends AbstractController
{
    /**
     * @Route(name="home", path="/")
     */
    public function accueil(SiteRepository $siteRepo, EtatRepository $etatRepo,SortieRepository $sortieRepo)
    {
        $sortie = $sortieRepo->findByDateDESC();

//        //recuperation de tous les sites
//        $sites = $siteRepo->findAll();
//        //recuperation de tous les etats
//        $etats = $etatRepo->findAll();

        return $this->render('main/index.html.twig', [
            'sorties' => $sortie,
//            'sites' => $sites,
//            'etats' => $etats
        ]);
    }
}
