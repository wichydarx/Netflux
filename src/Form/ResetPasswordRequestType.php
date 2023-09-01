<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordRequestType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator)
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,
             ['label' => 'Votre email',
                'required' => false,
                'constraints' => [
                    new Email(['message' => $this->translator->trans('Veuillez saisir un email valide')]),
                    new NotBlank(['message' => $this->translator->trans('ce champ est obligatoire')])
                ]])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
