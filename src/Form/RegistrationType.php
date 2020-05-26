<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,$this->getConfigration("Prénom","Votre prénom ..."))
            ->add('lastName',TextType::class,$this->getConfigration("nom","Votre nom de famille ..."))
            ->add('email',EmailType::class,$this->getConfigration("Email",'votre adresse email'))
            ->add('picture',UrlType::class,$this->getConfigration("Photo de profil","URL DE votre avatar ..."))
            ->add('hash',PasswordType::class,$this->getConfigration("mode de passe ","Choisissez un bon mot de passe !"))
            ->add('passwordConfirmer',PasswordType::class,$this->getConfigration("Confiemr mode de passe ","Choisissez un bon mot de passe !"))
            ->add('introduction',TextType::class,$this->getConfigration("Introducation","Presentez vous en quelques mots"))
            ->add('description',TextareaType::class,$this->getConfigration("Description","C'est le moment de vous présenter en détails !"))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
