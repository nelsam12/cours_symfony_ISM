<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Form\ClientType;
use App\enum\StatusDette;
use App\Dto\ClientSearchDto;
use App\Form\DetteFilterType;
use App\Form\SearchClientType;
use App\Repository\DetteRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

        $clientSearchDto = new ClientSearchDto(); // appel du constructeur
        $formSearch = $this->createForm(SearchClientType::class, $clientSearchDto);
        $formSearch->handleRequest($request);
        $page = $request->query->getInt('page', 1);
        $count = 0;
        $maxPage = 0;
        $limit = 6;
        if ($formSearch->isSubmitted($request) && $formSearch->isValid()) {
            // $formSearch->get('telephone')->getData()

            $clients = $clientRepository->findClientBy($clientSearchDto, $page, $limit);
        } else {
            $clients = $clientRepository->paginateClients($page, $limit);
        }
        $count = $clients->count();
        $maxPage = ceil($count / $limit);


        return $this->render('client/index.html.twig', [
            'datas' => $clients,
            'formSearch' => $formSearch->createView(),
            'page' => $page, // page actuelle
            'maxPage' => $maxPage,
        ]);
    }

    // Utilisation des path variables
    // Parameter facultatif  {name_param?}
    #[Route('/clients/show/{idClient?}', name: 'clients.show', methods: ['GET'])]
    public function show(int $idClient, ClientRepository $clientRepository, Request $request, DetteRepository $detteRepository): Response
    {
        $limit = 1;
        $page = $request->query->getInt('page', 1);

        $formSearch = $this->createForm(DetteFilterType::class);
        $formSearch->handleRequest($request);
        $client = $clientRepository->find($idClient);
        $status = $request->get("status", StatusDette::Impaye->value);

        if ($request->query->has('dette_filter')) {
            $status = $request->query->all('dette_filter')['status'];
        }
        $dettes = $detteRepository->findDetteByClient($idClient, $status, $limit, $page);

        $count = $dettes->count();
        $maxPage = ceil($count / $limit);
        return $this->render('client/dette.html.twig', [
            'dettes' => $dettes,
            'client' => $client,
            'status' => $status,
            'formSearch' => $formSearch->createView(),
            'page' => $page, // page actuelle
            'maxPage' => $maxPage,
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


    // #[Route('/clients/store', name: 'clients.store', methods: ['GET', 'POST'])]
    // public function store(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    // {
    //     $client = new Client();
    //     $client->setUser(new User());
    //     // Association de l'objet client au Formulaire
    //     $form = $this->createForm(ClientType::class, $client);
    //     // Récupération des données du formulaire
    //     $form->handleRequest($request);
    //     // Si le formulaire est soumis et valide
    //     if ($form->isSubmitted()) {


    //         $errorsClient = $validator->validate($client);
    //         $errorsUser = [];
    //         if (!$form->get('addUser')->getData()) {
    //             // Ajout d'un utilisateur avec le client
    //             $client->setUser(null);
    //         } else {
    //             $user = $client->getUser();
    //             $errorsUser = $validator->validate($user);
    //         }
    //         if (count($errorsClient) > 0 || count($errorsUser) > 0) {
    //             return $this->render('client/form.html.twig', [
    //                 'formClient' => $form->createView(),
    //                 'errorsClient' => $errorsClient,
    //                 'errorsUser' => $errorsUser,
    //             ]);
    //         }

    //         $entityManager->persist($client);
    //         // Executer la requête
    //         $entityManager->flush(); // commit the changes

    //         // Redirection vers la liste des clients
    //         return $this->redirectToRoute('clients.index');
    //     }
    //     return $this->render('client/form.html.twig', [
    //         'formClient' => $form->createView(),
    //     ]);
    // }

    // CTRL C + CTRL V de ce qui est en haut
    #[Route('/clients/store', name: 'clients.store', methods: ['GET', 'POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder): Response
    {
        $client = new Client();
        $client->setUser(new User());
        // Association de l'objet client au Formulaire
        $form = $this->createForm(ClientType::class, $client);
        // Récupération des données du formulaire
        $form->handleRequest($request);
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->get('addUser')->getData()) {
                // Ajout d'un utilisateur avec le client
                $client->setUser(null);
                
            }else{
                $user = $client->getUser();
                $hashedPassword = $encoder->hashPassword($user , $user->getPassword());
                $user->setPassword($hashedPassword);
            }

            $entityManager->persist($client);
            // Executer la requête
            $entityManager->flush(); // commit the changes

            // Redirection vers la liste des clients
            return $this->redirectToRoute('clients.index');
        }
        return $this->render('client/form2.html.twig', [
            'formClient' => $form->createView(),
        ]);
    }
}
