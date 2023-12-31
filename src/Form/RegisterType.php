<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
     public function __construct(
        private TranslatorInterface $translator)
    {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Email',
                    'attr' =>
                    [
                        'placeholder' => 'Email'
                    ]
                ]
            )
            ->add('password', RepeatedType::class, [
                'constraints' => new Length([
                    'min' => 5,
                ]),
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->trans
                ('Le mot de passe et la confirmation doivent être identique'),
                'first_options' => [
                    'label' => $this->translator->trans('Mot de passe'),
                    'attr' =>
                    [
                        'placeholder' => $this->translator->trans('Mot de passe')
                    ]
                ],
                'second_options' => [
                    'label' => $this->translator->trans('Confirmez votre mot de passe'),
                    'attr' =>
                    [
                        'placeholder' => $this->translator->trans('Confirmez votre mot de passe')
                    ]
                ]
            ])
            ->add(
                'lastname',
                TextType::class,
                [
                    'label' => $this->translator->trans('Nom'),
                    'attr' =>
                    [
                        'placeholder' => $this->translator->trans('Nom')
                    ]
                ]
            )
            ->add(
                'firstname',
                TextType::class,
                [
                    'label' => $this->translator->trans('Prénom'),
                    'attr' =>
                    [
                        'placeholder' => $this->translator->trans('Prénom')
                    ]
                ]
            )
            ->add(
                'avatar',
                FileType::class,
                [
                    'required' => false,
                    'label' => 'Avatar',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => $this->translator->trans("S'inscrire"),
                    'attr' =>
                    [
                        'class' => 'btn-warning'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
