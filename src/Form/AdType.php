<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    /**
     * permet d'voir la configration global
     * @param $label
     * @param $placeholder
     * @return array
     */
    private function getConfigration($label,$placeholder,$options=[]){
        return array_merge(
        [
        'label'=>$label,
                'attr'=>[
            'placeholder'=>$placeholder
        ]],$options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,$this->getConfigration('Titre','Taper un titre pour votre Annonce'))
            ->add('slug',TextType::class,$this->getConfigration('slug','taper adress web',[
                'required' =>false,
            ]))
            ->add('prix',MoneyType::class,$this->getConfigration('prix par nuit','Indiquer le prix'))
            ->add('introduction',TextType::class,$this->getConfigration('Introducation','Donnez une Description pour annonce'))
            ->add('content',TextareaType::class,$this->getConfigration('Description','Taper une description'))
            ->add('coverImage',UrlType::class,$this->getConfigration('Url de l\'image','Donnez adress'))
            ->add('nbchambre',IntegerType::class,$this->getConfigration('Nombre de Chambres','Le nombre de Chambre Disponible'))
            ->add('images',CollectionType::class,[
                'entry_type'=>ImageType::class,
                'allow_add'=>true,
                'allow_delete'=>true

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
