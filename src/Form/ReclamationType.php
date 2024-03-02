<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Name should have at least one character.']),
                    new Length(['min' => 1, 'minMessage' => 'Name should have at least one character.']),
                ],
                'required' => false,

            ])
            ->add('phonNumber', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Phone number should have at least one character.']),
                    new Length([
                        'min' => 1,
                        'max' => 15, // Set the maximum length to 15 characters
                        'minMessage' => 'Phone number should have at least one character.',
                        'maxMessage' => 'Phone number should have at most 15 characters.',
                    ]),
                    new Regex([
                        'pattern' => '/^00\d+$/',
                        'message' => 'Phone number should start with "00" and contain only digits.',
                    ]),
                ],
            ])
            ->add('email', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Email should have at least one character.']),
                    new Email(['message' => 'This is not a valid email address.']),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9._%+-]+@gmail\.[a-zA-Z]{2,}$/',
                        'message' => 'Email should be in the format something@gmail.something.',
                    ]),
                ],
                'required' => false,
            ])

            ->add('titre', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Title should have at least one character.']),
                    new Length(['min' => 1, 'minMessage' => 'Title should have at least one character.']),
                ],
                'required' => false,

            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Message should have at least one character.']),
                    new Length(['min' => 1, 'minMessage' => 'Message should have at least one character.']),
                ],
                'required' => false,
            ])
            ->add('createdAt', DateType::class, [
                'widget' => 'choice',
                'label'  => false, // Set label to false to hide it
                'input'  => 'datetime_immutable',
                'attr'   => ['style' => 'display:none;'], // Add this line to hide the field
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
