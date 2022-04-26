<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="accueil_")
 */
class AccueilController extends AbstractController
{
    /**
     * @Route(name="", path="")
     */
    public function Accueil()
    {
        return $this->render('main/index.html.twig');
    }
}
