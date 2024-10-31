<?php

namespace App\Twig\Components;

use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent]
class ClientFormComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ClientType::class);
    }

    #[LiveAction]
    public function store(EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder)
    {

        // Si le formulaire est soumis et valide
        $this->submitForm();
        $client = $this->getForm()->getData();
        $user = $client->getUser();
        // if ($user != null) {
        //     $hashedPassword = $encoder->hashPassword($user, $user->getPassword());
        //     $user->setPassword($hashedPassword);
        // }
        dd($client);
        

        $entityManager->persist($client);
        // Executer la requête
        $entityManager->flush(); // commit the changes

        // Redirection vers la liste des clients
        return $this->redirectToRoute('clients.index');
    }
}
