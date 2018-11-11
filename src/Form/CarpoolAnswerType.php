<?php

namespace App\Form;

use App\Entity\CarpoolAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class CarpoolAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', null, [
                'label' => "Votre nom"
            ])
            ->add('email', null, [
                'label' => "Votre e-mail"
            ])
            ->add('phone', null, [
                'label' => "Votre numéro de téléphone"
            ])
            ->add('nbrOfSeatsRequested', ChoiceType::class, [
                'label' => ($options['type'] == 'search' ? "Combien de places souhaitez-vous proposer ?" : "Combien de places souhaitez-vous réserver ?"),
                'choices' => array_combine(range(1, $options['maxSeats']), range(1, $options['maxSeats']))
            ])
            ->add('details', null, [
                'label' => ($options['type'] == 'search' ? "Détails de votre proposition" : "Détails de votre demande"),
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarpoolAnswer::class,
            'type' => 'search',
            'maxSeats' => 10
        ]);
    }
}
