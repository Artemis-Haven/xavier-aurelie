<?php

namespace App\Form;

use App\Entity\Gift;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class GiftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('giver', null, [
                'label' => 'Votre nom'
            ])
        ;
        if ($options['splittable']) {
            $builder
                ->add('amount', Type\TextType::class, [
                    'label' => 'Montant de votre contribution'
                ])
            ;
        }
        $builder
            ->add('message', null, [
                'label' => 'Vous pouvez si vous le souhaitez ajouter un petit message pour Xavier et Aurélie'
            ])
            ->add('onlinePayment', Type\ChoiceType::class, [
                'label' => "Souhaitez-vous payer en ligne maintenant ou donner l'argent le jour du mariage ?",
                'expanded' => true,
                'choices' => [
                    "Payer en ligne maintenant" => true,
                    "Donner l'argent le jour du mariage" => false
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gift::class,
            'splittable' => true
        ]);
    }
}
