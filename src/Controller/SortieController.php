<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\RechercheType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;

/**
 * @Route(name="sortie_", path="sortie/")
 */
class SortieController extends AbstractController
{
    /**
     * @Route(name="list", path="list", methods={"GET"})
     */
    public function list(Request $request, EntityManagerInterface $entityManager){

        $sortie = new Sortie();


        $formRechercheSortie = $this->createForm(RechercheType::class, $sortie);
        $formRechercheSortie->handleRequest($request);

        if($formRechercheSortie->isSubmitted() && $formRechercheSortie->isValid()){
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_list');
        }

        $sorties = $entityManager->getRepository('App:Sortie')->findAll();
//        dd($sorties);

        return $this->render('sortie/list.html.twig', [
            'sorties' => $sorties,
            'formRechercheSortie' =>$formRechercheSortie->createView(),
        ]);

    }


}