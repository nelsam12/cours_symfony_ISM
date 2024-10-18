<?php

namespace App\Form;

use App\Dto\ClientSearchDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('telephone', TextType::class, [
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Téléphone',
                    // 'pattern' => '^([77|78|76])[0-9]{7}$',
                    // 'class' => 'text-danger',
                ],
                'constraints' => [
                    new Regex(
                        '/^(77|78|76)([0-9]{7})$/',
                        'Le numéro de téléphone doit être au format 77XXXXXX ou 78XXXXXX ou 76XXXXXX'
                    )

                ]

            ])
            ->add('surname', TextType::class, [
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Surname',
                ]
            ])
            ->add('Search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success my-2 my-sm-0'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientSearchDto::class,
        ]);
    }
}
