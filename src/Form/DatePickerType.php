<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DatePickerType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
                    'widget' => 'single_text', // Important de mettre single_text
                    'html5' => true,
                    'format' => 'd/m/Y'
                ))
        ;
    }

    /**
     * Notre nouveau FormType est un dérivé de la classe DateType,
     * on va pouvoir utiliser sa configuration de base
     */
    public function getParent() {
        return DateType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     * C'est le nom du block qui sera repris dans la vue twig du FormType
     */
    public function getBlockPrefix() {
        return 'datepicker';
    }
    
}