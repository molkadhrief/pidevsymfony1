<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', null, [
                'label' => 'Subject',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a subject.']),
                ],
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Text',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter the text.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
