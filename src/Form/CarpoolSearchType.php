<?php

namespace App\Form;

use App\Entity\CarpoolSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CarpoolSearchType extends AbstractType
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
            ->add('nbrOfSeats', null, [
                'label' => "Nombre de places que vous recherchez"
            ])
            ->add('details', null, [
                'label' => "Détails concernant votre voyage",
                'attr' => [
                    'placeholder' => 'Exemples : "Besoin de place pour mes bagages", "Je peux me rendre jusqu\'au péage de l\'autoroute", "Je voyage avec un petit chien", ...',
                    'rows' => 8
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarpoolSearch::class,
        ]);
    }
}
