<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',PasswordType::class,
            $this->getConfigration('Ancien mot de passe','  ')
            )
            ->add('newPassword',PasswordType::class,
                $this->getConfigration('Nouveau mot de passe','  ')
            )
            ->add('confirmPassword',PasswordType::class,
                $this->getConfigration('Confirmation mot de passe','  ')
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
