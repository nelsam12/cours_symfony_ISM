<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DetteController extends AbstractController
{
    #[Route('/dettes', name: 'dettes.index')]
    public function index(): Response
    {
        return $this->render('dette/index.html.twig', [
            'controller_name' => 'DetteController',
        ]);
    }

  
}
