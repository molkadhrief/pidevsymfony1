<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Full Name',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'display:flex;margin-right: 30px;margin-bottom:5px;width : 300px;border-radius: 10px; border: 1px solid black; background-color: transparent;', // Apply custom styles
                    'placeholder' => 'Enter your full name', // Add placeholder text
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Your Email Address',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'display:flex;margin-right: 30px;margin-bottom:5px;width : 300px;border-radius: 10px; border: 1px solid black; background-color: transparent;', // Apply custom styles
                    'placeholder' => 'Enter your Email Address',
                ],])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Password',
                'required' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'display:flex;margin-right: 30px;margin-bottom:5px;width : 300px;border-radius: 10px; border: 1px solid black; background-color: transparent;', // Apply custom styles
                    'placeholder' => 'Enter your Password',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'message' => 'Your password must contain at least one digit',
                    ]),
                ],
            ])     
            ->add('phoneNumer', TextType::class, [
                'label' => 'Phone Number',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'display:flex;margin-right: 30px;margin-bottom:5px;width : 300px;border-radius: 10px; border: 1px solid black; background-color: transparent;', // Apply custom styles
                    'placeholder' => 'Enter your Phone Number',
                ],])
            ->add('adress', TextType::class, [
                'label' => 'Your Adress',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'display:flex;margin-right: 30px;margin-bottom:5px;width : 300px;border-radius: 10px; border: 1px solid black; background-color: transparent;', // Apply custom styles
                    'placeholder' => 'Enter your Adress',
                ],])
            
            /*->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'choices' => [
                    'USER' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true, // Permet de sélectionner un seul choix
                'expanded' => true, // Affiche les choix sous forme de boutons radio
                
            ])*/
            ->add("captcha" , RecaptchaType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                
            ])
            
        
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}