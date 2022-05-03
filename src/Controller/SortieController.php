<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\RechercheType;
use App\Form\SortieType;
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

//        dd($sortiesQuery);

        $sortie = $sortieRepo->findByUtilisateurSite($this->getUser()->getSiteId()->getId());


        //recuperation de tous les sites
        $sites = $siteRepo->findAll();
        //recuperation de tous les etats
        $etats = $etatRepo->findAll();



        //délégation du travail au twig liste.html.twig en y passant en parametre les sorties filtrées, les sites et les etats

        //délégation du travail au twig liste.html.twig en y passant en parametre les sorties filtrées, les sites et les etats
        return $this->render("sortie/list.html.twig", [

            'sorties' => $sortie,
            'sites' => $sites,
            'etats' => $etats
        ]);
//
//        }


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



    /**
     * @Route(name="listRecherche", path="listRecherche", methods={"GET", "POST"})
     */
    public function listRecherche(Request $request, EntityManagerInterface $entityManager, SiteRepository $siteRepo, EtatRepository $etatRepo, SortieRepository $sortieRepo)
    {

//        $sortie = $entityManager->getRepository('App:Sortie')->findByUtilisateurSite($this->getUser()->getSiteId()->getId());


        $sortiesQuery = $sortieRepo->recherche(
            ($request->query->get('recherche_terme') != null ? $request->query->get('recherche_terme') : null),
            ($request->query->get('recherche_site') != null ? $request->query->get('recherche_site') : null),
            ($request->query->get('recherche_etat') != null ? $request->query->get('recherche_etat') : null),
            ($request->query->get('date_debut') != null ? $request->query->get('date_debut') : null),
            ($request->query->get('date_fin') != null ? $request->query->get('date_fin') : null),
            ($request->query->get('cb_organisateur') != null ? $request->query->get('cb_organisateur') : null),
            ($request->query->get('cb_inscrit') != null ? $request->query->get('cb_inscrit') : null),
            ($request->query->get('cb_non_inscrit') != null ? $request->query->get('cb_non_inscrit') : null),
            ($request->query->get('cb_passee') != null ? $request->query->get('cb_passee') : null)
        );
//        dd($sortiesQuery);


        //recuperation de tous les sites
        $sites = $siteRepo->findAll();
        //recuperation de tous les etats
        $etats = $etatRepo->findAll();

//            if ($sortiesQuery ==null)
//            {
//                $sortiesQuery = $sortieRepo->findAll();
//            }

        return $this->render("sortie/listRecherche.html.twig", [

            'sorties' => $sortiesQuery,
            'sites' => $sites,
            'etats' => $etats
        ]);


    }

    /**
     * @Route(name="creer", path="creer", methods={"GET", "POST"})
     */
    public function creerSortie(Request $request, EntityManagerInterface $entityManager){

        //Création de l'entité
        $sortie = new Sortie();

        // Association de l'entité au formulaire
        $formCreation = $this->createForm(SortieType::class, $sortie);

        $formCreation->handleRequest($request);

        //Vérification de la soumission du formulaire
        if ($formCreation->isSubmitted() && $formCreation->isValid()){

            // Association de l'objet
            $entityManager->persist($sortie);

            //Validation de la transaction
            $entityManager->flush();

            //Ajouter un message de confirmation
            $this->addFlash('success', 'Votre sortie a bien été créée !');

            // Redirection de l'utilisateur sur l'accueil
            return $this->redirectToRoute('accueil_home');
        }
        else{
            //Ajouter un message d'erreur'
            $this->addFlash('error', 'Votre sortie n"a pas été créée !');
        }

        // Envoi du formulaire à la vue
        return $this->render('sortie/creation.html.twig', [
            'formCreation' => $formCreation->createView(),
        ]);


    }

    /**
     * @Route(name="inscription", path="inscription", methods={"GET", "POST"})
     */
    public function inscription(Request $request, EntityManagerInterface $entityManager){

        $participant = $entityManager->getRepository('App:Participant')
            ->findOneBy("id", $this->getUser()->getId());
        


    }

    /**
     * @Route(name="detail", path="{id}/detail", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function details(Request $request, EntityManagerInterface $entityManager, SortieRepository $sr) {

        // Récupération de l'identifiant de la sortie
        $id = (int) $request->get('id');

        // Récupération de la sortie souhaité
        $sortie = $sr->findById($id);
       // dd($id);
        if (is_null($sortie)) {
            throw $this->createNotFoundException('Sortie Non trouvée !');
        }

        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    }