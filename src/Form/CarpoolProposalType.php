<?php

namespace App\Form;

use App\Entity\CarpoolProposal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CarpoolProposalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', null, [
                'label' => "Votre nom"
            ])
            ->add('email', null, [
                'label' => "Votre e-mail (pour le suivi des réponses à votre annonce)"
            ])
            ->add('phone', null, [
                'label' => "Votre numéro de téléphone"
            ])
            ->add('location', null, [
                'label' => "Votre ville de départ (ou d'arrivée, en cas de retour uniquement)"
            ])
            ->add('direction', ChoiceType::class, [
                'choices' => [
                    null => "",
                    "Aller uniquement" => "Aller uniquement", 
                    "Retour uniquement" => "Retour uniquement", 
                    "Aller/retour" => "Aller/retour"]
            ])
            ->add('nbrOfSeats', ChoiceType::class, [
                'label' => "Nombre de places que vous proposez",
                'choices' => array_combine(range(1, $options['maxSeats']), range(1, $options['maxSeats']))
            ])
            ->add('details', null, [
                'label' => "Détails concernant votre voyage",
                'required' => false,
                'attr' => ['placeholder' => 'Exemples : "Peu de place pour les bagages", "Rendez-vous au péage de l\'autoroute", "Voyage non fumeur", ...']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarpoolProposal::class,
            'maxSeats' => 10
        ]);
    }
}
