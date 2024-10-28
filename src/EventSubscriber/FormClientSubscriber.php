<?php

namespace App\EventSubscriber;

use App\Form\UserType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FormClientSubscriber implements EventSubscriberInterface
{
    // On pre-submit ce qui se passe lorsqu'on envoie le formulaire
    //  c'est pour ça on a envoyé avec le js
    // pour donner l'impression que c'est le JS qui charge le formulaire
    // mais c'est le subscriber qui nous donne le formulaire
    // maintenant, il va falloir refaire la même chose que ce qu'on avait fait dans le controller
    // Effectivement, mais il le fait en Back
    public function onFormPreSubmit(PreSubmitEvent $event): void
    {
        $formData = $event->getData(); // Récupère les données du formulaire
        $form = $event->getForm();
        if (isset($formData['addUser']) && $formData['addUser'] == "1") {
            $form
                ->add('user', UserType::class, [
                    'label' => false,
                    'attr' => [],
                ]);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'form.pre_submit' => 'onFormPreSubmit',
            'form.pre_set_data' => 'onFormPreSetData',
        ];
    }

    public function onFormPreSetData(PreSetDataEvent $event): void
    {
        
    }
}
