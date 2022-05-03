<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\RechercheType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
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


        $sortie = $sortieRepo->findByUtilisateurSite($this->getUser()->getSiteId()->getId());

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


        //recuperation de tous les sites
        $sites = $siteRepo->findAll();
        //recuperation de tous les etats
        $etats = $etatRepo->findAll();


        if ($_SERVER['REQUEST_URI'] == "/sortie/list"){
            return $this->render("sortie/list.html.twig", [

                'sorties' => $sortie,
                'sites' => $sites,
                'etats' => $etats
            ]);
        }

        else{
            return $this->render("sortie/list.html.twig", [

                'sorties' => $sortiesQuery,
                'sites' => $sites,
                'etats' => $etats
            ]);
        }


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
     * @Route(name="inscription", path="{id}/inscription", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function inscription(Request $request, EntityManagerInterface $em, ParticipantRepository $pr, SortieRepository $sr){

        $sortie = $sr->find('id');
        $participant = $pr->findById($this->getUser()->getId());
        $sortie = $sr->findByID($idSortie);
        $participant->addInscription($sortie);
        $sortie->addInscrit($sortie);
        $sortie->addNbInscrits();
        $em->flush();

        return $this->render('sortie/detail.html.twig', [ 'sortie' => $sortie,
        ]);
    }
    /**
     * @Route("/inscription/{idSortie}/{idParticipant}", name="app_sortie_inscription",methods={"GET", "POST"})
     *
     */
    public function inscription(Request $request, SortieRepository $sortieRepository, ParticipantRepository $participantRepository): Response
    {
        $sortie = $sortieRepository->find((int)$request->get('idSortie'));
        $participant = $participantRepository->find($request->get('idParticipant'));
        $sortie->addParticipant($participant);
        $sortie->addParticipantNoParticipant($participant);
        $sortieRepository->add($sortie);
        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER );
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