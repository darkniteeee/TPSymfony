<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="participant_", path="participant/")
 */
class ParticipantController extends AbstractController
{

    /**
     * @Route(name="modifier_profil", path="modifier_profil", methods={"GET", "POST"})
     */
    public function modifierProfil(Request $request, EntityManagerInterface $entityManager): Response{

        //Récupération de l'entité
        $participant = $this->getUser();

        // Association de l'entité au formulaire
        $formProfil = $this->createForm(ProfilType::class, $participant);
        $formProfil->handleRequest($request);

        //Vérification de la soumission du formulaire
        if ($formProfil->isSubmitted() && $formProfil->isValid()){

            //Début photo de profil du formulaire
//            $photo_profil_file = $formProfil->get('photo_profil')->getData();
//
//            if($photo_profil_file){}


        //Validation de la transaction
            $entityManager->flush();

            //Ajouter un message de confirmation
            $this->addFlash('success', 'Le profil a bien été modifié !');
        }

        // Envoi du formulaire à la vue
        return $this->render('participant/profil.html.twig', [
            'formProfil' => $formProfil->createView(),
        ]);


}

    /**
     * @Route(name="inscrire", path="inscrire", methods={"GET", "POST"})
     */
    public function inscrire(Request $request, EntityManagerInterface $entityManager){

        //Création de l'entité
        $participant = new Participant();

        // Association de l'entité au formulaire
        $formInscription = $this->createForm(InscriptionType::class, $participant);
        $formInscription->handleRequest($request);

        //Vérification de la soumission du formulaire
        if ($formInscription->isSubmitted() && $formInscription->isValid()){

            //Début photo de profil du formulaire
//            $photo_profil_file = $formProfil->get('photo_profil')->getData();
//
//            if($photo_profil_file){}

            // Association de l'objet
            $entityManager->persist($participant);

            //Validation de la transaction
            $entityManager->flush();

            //Ajouter un message de confirmation
            $this->addFlash('success', 'Votre profil a bien été créé !');

            // Redirection de l'utilisateur sur la liste
            return $this->redirectToRoute('main/index.html.twig');
        }

        // Envoi du formulaire à la vue
        return $this->render('participant/inscrire.html.twig', [
            'formInscription' => $formInscription->createView(),
        ]);


    }

}