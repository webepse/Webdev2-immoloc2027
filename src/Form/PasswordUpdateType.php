<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfiguration("Mot de passe", "Votre ancien mot de passe"))
            ->add('newPassword', PasswordType::class, $this->getConfiguration("Mot de passe", "Votre nouveau mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation du nouveau mot de passe", "Veuillez confirmer votre mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
