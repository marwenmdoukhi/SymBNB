<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType{

    /**
     * permet d'voir la configration global
     * @param $label
     * @param $placeholder
     * @return array
     */
    Protected function getConfigration($label,$placeholder,$options=[]){
        return array_merge(
            [
                'label'=>$label,
                'attr'=>[
                    'placeholder'=>$placeholder
                ]],$options);
    }

}




