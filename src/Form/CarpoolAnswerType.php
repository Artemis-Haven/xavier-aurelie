<?php

namespace App\Form;

use App\Entity\CarpoolAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarpoolAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', null, [
                'label' => "Votre nom"
            ])
            ->add('nbrOfSeatsRequested')
            ->add('details')
            ->add('email')
            ->add('phone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarpoolAnswer::class,
        ]);
    }
}
