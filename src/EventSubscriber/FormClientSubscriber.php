<?php

namespace App\EventSubscriber;

use App\Form\UserType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FormClientSubscriber implements EventSubscriberInterface
{
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
