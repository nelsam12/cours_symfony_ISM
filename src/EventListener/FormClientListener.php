<?php

namespace App\EventListener;

use App\Form\UserType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class FormClientListener
{
    #[AsEventListener(event: 'form.pre_submit')]
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
}
