<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Client;
use App\Form\UserType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use App\EventSubscriber\FormClientSubscriber;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('telephone', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => '773461882',
                    // 'pattern' => '^([77|78|76])[0-9]{7}$',
                    // 'class' => 'text-danger',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un numéro de téléphone valide.',
                    ]),
                    new NotNull([
                        'message' => 'Le téléphone ne peut pas être vide',
                    ]),
                    new Regex(
                        '/^(77|78|76)([0-9]{7})$/',
                        'Le numéro de téléphone doit être au format 77XXXXXX ou 78XXXXXX ou 76XXXXXX'
                    )

                ]

            ])
            ->add('surname', TextType::class, [
                'required' => false,
            ])
            ->add('adresse', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une adresse valide.',
                    ]),
                ]
            ])
            ->add('addUser', CheckboxType::class, [
                'label' => 'Ajouter un compte ?',
                'required' => false,
                'data' => false,
                'mapped' => false,

                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])

            // ->add('Save', SubmitType::class)

            // ->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event): void {
            //     $formData = $event->getData(); // Récupère les données du formulaire
            //     $form = $event->getForm();
            //     // dd($form);
            //     if (isset($formData['addUser']) && $formData['addUser'] == "1") {

            //         $form
            //             ->add('user', UserType::class, [
            //                 'label' => false,
            //                 'attr' => [],
            //             ]);
            //     }
            // })

            ->addEventSubscriber(new FormClientSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'validation_groups' => ['Default'],
        ]);
    }
}
