<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\RechercheType;
use App\Repository\EtatRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;

/**
 * @Route(name="sortie_", path="sortie/")
 */
class SortieController extends AbstractController
{
    /**
     * @Route(name="list", path="list", methods={"GET", "POST"})
     */
    public function list(Request $request, EntityManagerInterface $entityManager, SiteRepository $siteRepo, EtatRepository $etatRepo, SortieRepository $sortieRepo){

//        $sortie = $entityManager->getRepository('App:Sortie')->findByUtilisateurSite($this->getUser()->getSiteId()->getId());


        $sortiesQuery = $sortieRepo->recherche(
            ($request->query->get('recherche_terme') != null ? $request->query->get('recherche_terme') : null),
            ($request->query->get('recherche_site') != null ? $request->query->get('recherche_site') : null),
            ($request->query->get('recherche_etat') != null ? $request->query->get('recherche_etat') : null),
            ($request->query->get('dateDeDebut') != null ? $request->query->get('dateDeDebut') : null),
            ($request->query->get('dateDeFin') != null ? $request->query->get('dateDeFin') : null),
            ($request->query->get('organisateur') != null ? $request->query->get('cb_organisateur') : null),
            ($request->query->get('cb_inscrit') != null ? $request->query->get('cb_inscrit') : null),
            ($request->query->get('cb_non_inscrit') != null ? $request->query->get('cb_non_inscrit') : null),
            ($request->query->get('cb_passee') != null ? $request->query->get('cb_passee') : null)
        );



        //recuperation de tous les sites
        $sites = $siteRepo->findAll();
        //recuperation de tous les etats
        $etats = $etatRepo->findAll();

        //délégation du travail au twig liste.html.twig en y passant en parametre les sorties filtrées, les sites et les etats
        return $this->render("sortie/list.html.twig", [

            'sorties' => $sortiesQuery,
//            'sorties' => $sortie,
            'sites' => $sites,
            'etats' => $etats
        ]);



//        $sortie = new Sortie();
//
//
//        $formRechercheSortie = $this->createForm(RechercheType::class, $sortie);
//        $formRechercheSortie->handleRequest($request);
//
//        if($formRechercheSortie->isSubmitted() && $formRechercheSortie->isValid()){
//            $entityManager->getRepository('App:Sortie');
//            $entityManager->flush();



//        $sortie = $entityManager->getRepository('App:Sortie')->findByUtilisateurSite($this->getUser()->getSiteId()->getId());
////        $participants = $entityManager->getRepository('App:Participant')->findAll();
////        dd($sorties);
//
//
//
//        return $this->render('sortie/list.html.twig', [
//
////            'participants' => $participants,
//
//            'formRechercheSortie' =>$formRechercheSortie->createView(),
//        ]);

    }


}