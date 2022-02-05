<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,
                [
                    'label'=> false,
                    'attr'=>[
                        'class'=>'form-control',
                        'placeholder'=>'Entre une adresse mail'
                    ]
                ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' =>[
                    'class'=>'form-control',
                    'placeholder'=>'Entre un mot de passe'

                ]])
            ->add('lastname', TextType::class ,
                [
                    'label'=> false,
                    'attr'=>[
                        'class'=>'form-control',
                        'placeholder'=>'Votre nom'

                    ]
                ])
            ->add('firstname', TextType::class ,
                [
                    'label'=> false,
                    'attr'=>[
                        'class'=>'form-control',
                        'placeholder'=>'Entre votre nom'

                    ]
                ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => false,
                'attr' => ['autocomplete' => 'new-password',
                    'placeholder'=>'Confirmer mots de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),

                ],
            ])
            ->add('role', ChoiceType::class, [
                'mapped' => false,
                'label' => false,
                'choices'  => [
                    'Client' => 'ROLE_CLIENT',
                    'Gestionnaire' => "ROLE_G",
                    'Administrator' => 'ROLE_ADMINISTRATOR',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
