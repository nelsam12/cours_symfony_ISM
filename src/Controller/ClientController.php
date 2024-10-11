<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientController extends AbstractController
{
    #[Route('/clients', name: 'clients.index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        /* 
            Methodes de répository permet de récupérer les données d'une entité :
                findAll() : Retourne tous les objets de la classe
                find($id) : Retourne un objet unique grâce à son id
                findBy(['field' => 'value']) : Retourne une liste d'objets en fonction d'un ou plusieurs champs
                findBy(['field1' => 'value1', 'field2' => 'value2']) : Retourne une liste d'objets en fonction de plusieurs champs
                findOneBy(['field' => 'value']) : Retourne un objet unique en fonction d'un ou plusieurs champs
                findOneBy(['field1' => 'value1', 'field2' => 'value2']) : Retourne un objet unique en fonction de plusieurs champs
                findOneBy(['field' => 'value'], ['order_field' => 'ASC']) : Retourne un objet unique en fonction d'un ou plusieurs champs et tri
        */
        $clients = $clientRepository->findAll();
        return $this->render('client/index.html.twig', [
            'datas' => $clients
        ]);
    }

    // Utilisation des path variables
    // Parameter facultatif  {name_param?}
    #[Route('/clients/show/{id?}', name: 'clients.show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    // Utilisation des query params
    #[Route('/clients/search/telephone', name: 'clients.searchClientByTelephone', methods: ['GET'])]
    public function searchlientByTelephone(Request $request): Response
    {
        // query => $_GET
        // request => $_POST
        // $request->query->get('key') => $_GET['key']
        // $request->request->get('name_field') => $_POST['name_field']

        $telephone = $request->query->get('tel');
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/clients/remove/{id?}', name: 'clients.remove', methods: ['GET'])]
    public function remove(int $id): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/clients/store', name: 'clients.store', methods: ['GET', 'POST'])]
    public function store(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
}
