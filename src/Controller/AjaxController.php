<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="ajax_", path="ajax/")
 */
class AjaxController extends AbstractController
{
    /**
     * @Route(path="rechercheLieuByVille", name="rechercher_lieu_by_ville")
     */
    public function rechercheAjaxByVille(Request $request , EntityManagerInterface $entityManager, LieuRepository  $lieuRepository){
        //declaration des variables
        $json_data = array();
        $i = 0;
        //recherche les lieux correspondant à la ville selectionnée
        $lieux = $lieuRepository->findBy(['ville' => $request->request->get('ville_id')]);
        //dd($lieux);
        //si lieux trouvées ...
        if(sizeof($lieux)> 0){
            //pour chaque lieu, hydratation d'un tableau
            foreach ($lieux as $lieu){
                $json_data[$i++] = array( 'id' => $lieu->getId(), 'nom' => $lieu->getNomLieu());
            }
            //renvoie un tableau au format json
            return new JsonResponse($json_data);
            //sinon (lieux non trouvé) ...
        }else{
            //hydratation du tableau avec : Pas de lieu correspondant à cette ville.
            $json_data[$i++] = array( 'id' => '', 'nom' => 'Pas de lieu correspondant à cette ville.');
            //renvoie un tableau au format json
            return new JsonResponse($json_data);
        }
    }




}