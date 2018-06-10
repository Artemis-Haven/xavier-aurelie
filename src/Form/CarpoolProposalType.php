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
            ->add('author')
            ->add('location')
            ->add('direction', ChoiceType::class, [
                'choices' => [
                    "Aller uniquement" => "Aller uniquement", 
                    "Retour uniquement" => "Retour uniquement", 
                    "Aller/retour" => "Aller/retour"]
            ])
            ->add('details')
            ->add('nbrOfPersons')
            ->add('email')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarpoolProposal::class,
        ]);
    }
}
