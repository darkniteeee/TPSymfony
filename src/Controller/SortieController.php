<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\RechercheType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
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

     //Fonction permettant de d'afficher les sorties repondant aux critères de filtre
    /**
     * @Route(name="list", path="list", methods={"GET"})
     */
    public function list(Request $request, EntityManagerInterface $entityManager, SiteRepository $siteRepo, EtatRepository $etatRepo, SortieRepository $sortieRepo){

        //Récupération de toutes les sorties lier au site de rattachement de l'utilisateur
        $sortie = $sortieRepo->findByUtilisateurSite($this->getUser()->getSiteId()->getId());

        //utilisation d'une requête queryBulder qui permet de filtrer les sorties
        //en fonction de différent critère de recherche récupéré depuis le twig list.html.twig

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


        //délégation du travail au twig list.html.twig en y passant en paramètre
        //les sorties lier au site de rattachement de l'utilisateur, les sites et les états
        //si l'url est égale à /sortie/liste
        //sinon les sortie filtrées, les sites et les états

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
    public function creerSortie(Request $request, EntityManagerInterface $entityManager, SiteRepository $siteRepo, EtatRepository $etatRepo){

        //Création de l'entité
        $sortie = new Sortie();
        $sortie->setOrganisateur($this->getUser());
        $sortie->setSiteOrganisateur($siteRepo->find($this->getUser()->getSiteId()->getId()));
        $sortie->setEtat($etatRepo->find(1));

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
            return $this->redirectToRoute('sortie_list');
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
     * @Route(name="annuler", path="{id}/annuler", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function annulerSortie(Request $request, EntityManagerInterface $em, SortieRepository $sr, EtatRepository $etatRepo)
    {
        $sortie = $sr->find((int) $request->get('id'));

        if (!$sortie == null) {
            $sortie->setEtat($etatRepo->find(6));
            $em->flush();

            //Ajouter un message de confirmation
            $this->addFlash('success', 'Votre sortie a bien été annulée!');

            // Redirection de l'utilisateur sur la liste des sorties
            return $this->redirectToRoute('sortie_list');
        }else{
            //Ajouter un message d'erreur'
            $this->addFlash('error', 'Votre sortie n"a pas pu être annulée... Veuillez contacter l"administrateur.');

		// Redirection de l'utilisateur sur la liste des sorties
		return $this->redirectToRoute('sortie_detail');
        }

    }

    /**
     * @Route(name="inscription", path="{id}/inscription", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function inscription(Request $request, EntityManagerInterface $em, ParticipantRepository $pr, SortieRepository $sr){

//        try {
            $sortie = $sr->find((int)$request->get('id'));
            $participant = $pr->find($this->getUser()->getId());
            $sortie->addInscrit($participant);
            $sortie->addNbInscrits();
//        }catch (Exception $e) {
//            $this->addFlash("Error", "Erreur : Inscription annulée".$e->getMessage());
//        }
        $em->flush();
        return $this->render('sortie/detail.html.twig', [ 'sortie' => $sortie,
        ]);
    }
    /**
     * @Route(name="desinscrire", path="{id}/desinscrire", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function desinscrire (Request $request, EntityManagerInterface $em, SortieRepository $sr, ParticipantRepository $pr)
    {
        $sortie = $sr->find((int) $request->get('id'));
        $participant = $pr->find($this->getUser()->getId());
        $sortie -> removeInscrit($participant);
        $sortie -> minNbInscrits();
        $participant -> removeInscription($sortie);
        $em -> flush();
        return $this->redirectToRoute('sortie_list');
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