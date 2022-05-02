<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\InscriptionType;
use App\Form\ModifierPasswordType;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function inscrire(Request $request, UserPasswordHasherInterface $participantPasswordHasher, EntityManagerInterface $entityManager){

        //Création de l'entité
        $participant = new Participant();

        // Association de l'entité au formulaire
        $formInscription = $this->createForm(InscriptionType::class, $participant);

        $formInscription->handleRequest($request);

        //Vérification de la soumission du formulaire
        if ($formInscription->isSubmitted() && $formInscription->isValid()){

            // Hashage du mot de passe
            $participant->setPassword($participantPasswordHasher->hashPassword($participant, $participant->getPassword()));

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

            // Redirection de l'utilisateur sur l'accueil
            return $this->redirectToRoute('accueil_home');
        }

        // Envoi du formulaire à la vue
        return $this->render('participant/inscription.html.twig', [
            'formInscription' => $formInscription->createView(),
        ]);


    }

    /**
     * @Route(name="modifier_password", path="modifier_password", methods={"GET", "POST"})
     */
    public function modifierPassword(Request $request, UserPasswordHasherInterface $participantPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        //Récupération de l'entité
        $participant = $this->getUser();

        // Association de l'entité au formulaire

        $formModifierPassword= $this->createForm(ModifierPasswordType::class);

        $formModifierPassword->handleRequest($request);


        //Vérification de la soumission du formulaire
        if ($formModifierPassword->isSubmitted() && $formModifierPassword->isValid()){

           $oldPassword = $_POST['modifier_password']['password'];

           $participant=$this->getUser();
            if($participantPasswordHasher->isPasswordValid($participant, $formModifierPassword["password"]->getData())){
                // Hashage du mot de passe
                $participant->setPassword($participantPasswordHasher->hashPassword($participant, $formModifierPassword["newPassword"]->getData()));

                //Validation de la transaction
                $entityManager->flush();

                //Ajouter un message de confirmation
                $this->addFlash('success', 'Le mot de passe a bien été modifié !');
                return $this->redirectToRoute('accueil_home');
            }
            else{
                $this->addFlash('Error', 'mot de passe incorrect !');
            }

        }

        // Envoi du formulaire à la vue
        return $this->render('participant/modifier_password.html.twig', [
            'formModifierPassword' => $formModifierPassword->createView(),
        ]);


    }

}