<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\RechercheType;
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
     * @Route(name="list", path="list", methods={"GET"})
     */
    public function list(Request $request, EntityManagerInterface $entityManager, UserInterface $user){

        $sortie = new Sortie();


        $formRechercheSortie = $this->createForm(RechercheType::class, $sortie);
        $formRechercheSortie->handleRequest($request);

        if($formRechercheSortie->isSubmitted() && $formRechercheSortie->isValid()){
            $entityManager->getRepository('App:Sortie')->re();
            $entityManager->flush();

            return $this->redirectToRoute('sortie_list');
        }

        $sorties = $entityManager->getRepository('App:Sortie')->findBytruck(1);
//        $participants = $entityManager->getRepository('App:Participant')->findAll();
//        dd($sorties);



        return $this->render('sortie/list.html.twig', [
            'sorties' => $sorties,
//            'participants' => $participants,

            'formRechercheSortie' =>$formRechercheSortie->createView(),
        ]);

    }


}