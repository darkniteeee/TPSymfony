<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\InscriptionType;
use App\Form\ModifierPasswordType;
use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
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
    public function modifierProfil(Request $request, ParticipantRepository $participantRepository, UserPasswordHasherInterface $participantPasswordHasher): Response{

        //Récupération de l'entité
        $participant = $this->getUser();

        // Association de l'entité au formulaire
        $formProfil = $this->createForm(ProfilType::class, $participant);
        $formProfil->handleRequest($request);

        //Vérification de la soumission du formulaire
        if ($formProfil->isSubmitted() && $formProfil->isValid()){

            if($formProfil->get('photo_profil')->getData() != null) {
                $file = $formProfil->get('photo_profil')->getData();

                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
//                dd($fileName);
                $file->move($this->getParameter('users_photos_directory'), $fileName);
                $participant->setPhotoProfil($fileName);
            }

            //Vérification password BD et entrée utilisateur

            if($participantPasswordHasher->isPasswordValid($participant, $formProfil->getData()->getPlainPassword())){



             $participantRepository->add($participant);




                //Ajouter un message de confirmation
                $this->addFlash('success', 'Le profil a bien été modifié !');
                return $this->redirectToRoute('accueil_home');
            }
            else{
                $this->addFlash('alert alert-danger', 'ERREUR : mot de passe incorrect !');
            }



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
            $password = $participant->getPassword();

            // Vérification du mot de passe hors assert
            $regex = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{12,}$/";
            if(!preg_match($regex, $password)){
                $error['password'] = "Le mot de passe doit contenir 12 lettres y compris un chiffre !";
                $this->addFlash('warning', 'Le mot de passe doit contenir 12 lettres y compris un chiffre !');
                return $this->render('participant/inscription.html.twig',
                    [
                        'formInscription' => $formInscription->createView(),
                        'error'=> $error,

                    ]);
            }
            //pour gwendo

            // Hashage du mot de passe
            $participant->setPassword($participantPasswordHasher->hashPassword($participant, $participant->getPassword()));

            if($participant->getAdministrateur() == true ){
                $participant->addRole("ROLE_ADMIN");
            }
            else{
                $participant->addRole("ROLE_USER");
            }
            // Association de l'objet
            $entityManager->persist($participant);

            //Validation de la transaction
            $entityManager->flush();

            //Ajouter un message de confirmation
            $this->addFlash('success', 'Le profil a bien été créé !');

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
            $participant=$this->getUser();
            $newPassword = $formModifierPassword->get('newPassword')->getData();

            // Vérification du mot de passe hors assert
            $regex = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{12,}$/";
            if(!preg_match($regex, $newPassword)){

                // $error['password'] = "Le mot de passe doit contenir 12 lettres y compris un chiffre !";
                $this->addFlash('warning', 'Le mot de passe doit contenir 12 lettres y compris un chiffre !');

                return $this->render('participant/modifier_password.html.twig', [
                    'formModifierPassword' => $formModifierPassword->createView(),
                    //'error'=> $error

                ]);
            }

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
                    $this->addFlash('alert alert-danger', 'mot de passe incorrect !');
                }
        }

        // Envoi du formulaire à la vue
        return $this->render('participant/modifier_password.html.twig', [
            'formModifierPassword' => $formModifierPassword->createView(),
        ]);

    }

    /**
     * @Route(name="afficher_profil", path="{idp} {ids}/afficher_profil", requirements={"id": "\d+"}, methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ParticipantRepository $pr
     * @return Response
     */
    public function afficherProfil(Request $request, EntityManagerInterface $em, ParticipantRepository $pr, SortieRepository $sr ){
        $participant = $pr->find((int) $request->get('idp'));
        $sortie = $sr->find((int) $request->get('ids'));

        // Envoi du formulaire à la vue
        return $this->render('participant/profil_participant.html.twig',
            [
                'participant'=> $participant,
                'sortie'=>$sortie,
            ]
        );
    }

}
