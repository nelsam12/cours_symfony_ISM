<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Form\SearchClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class ClientController extends AbstractController
{
    #[Route('/clients', name: 'clients.index', methods: ['GET', 'POST'])]
    public function index(ClientRepository $clientRepository, Request $request): Response
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

        $formSearch = $this->createForm(SearchClientType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted($request) && $formSearch->isValid()){
            $clients = $clientRepository->findBy(['telephone' => $formSearch->get('telephone')->getData()]);
        }else{
            $clients = $clientRepository->findAll();
        }

        
        return $this->render('client/index.html.twig', [
            'datas' => $clients,
            'formSearch' => $formSearch->createView()
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
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {


        $client = new Client();
        // Association de l'objet client au Formulaire
        $form = $this->createForm(ClientType::class, $client);
        // Récupération des données du formulaire
        $form->handleRequest($request);
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde des données du formulaire dans la base de données
            $client->setCreateAt(new \DateTimeImmutable());
            $client->setUpdateAt(new \DateTimeImmutable());

            $entityManager->persist($client);
            // Executer la requête
            $entityManager->flush(); // commit the changes

            // Redirection vers la liste des clients
            return $this->redirectToRoute('clients.index');
        }
        return $this->render('client/form.html.twig', [
            'formClient' => $form->createView(),
        ]);
    }
}
