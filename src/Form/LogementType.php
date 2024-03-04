<?php

namespace App\Form;

use App\Entity\Logement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class LogementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['placeholder' => 'Enter le nom ..'],
                'constraints' => [
                    new NotBlank(['message' => 'This field cannot be empty.']),
                    new Regex([
                        'pattern' => '/\d/', // Disallow numbers
                        'match' => false,
                        'message' => 'Numbers are not allowed in this field.',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'attr' => ['placeholder' => 'Enter la description..'],
                'constraints' => [
                    new NotBlank(['message' => 'This field cannot be empty.']),
                    new Regex([
                        'pattern' => '/\d/', // Disallow numbers
                        'match' => false,
                        'message' => 'Numbers are not allowed in this field.',
                    ]),
                ],
            ])
            ->add('place', TextType::class, [
                'attr' => ['placeholder' => 'Enter le nbr place..'],
                'constraints' => [
                    new NotBlank(['message' => 'This field cannot be empty.']),
                ],
            ])
            ->add('prix', TextType::class, [
                'attr' => ['placeholder' => 'Enter le nbr place..'],
                'constraints' => [
                    new NotBlank(['message' => 'This field cannot be empty.']),
                ],
            ])
            ->add('categorie');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Logement::class,
        ]);
    }
}
