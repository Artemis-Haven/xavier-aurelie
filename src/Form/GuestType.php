<?php

namespace App\Form;

use App\Entity\Guest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => "Prénom"],
                'by_reference' => false
            ])
            ->add('lastname', null, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => "Nom"],
                'by_reference' => false
            ])
            ->add('attendCeremony', CheckboxType::class, [
                'label' => 'Participe à la cérémonie du 30 mai',
            ])
            ->add('attendBrunch', CheckboxType::class, [
                'label' => 'Participe au brunch du lendemain',
            ])
            ->add('accommodationOnSite', CheckboxType::class, [
                'label' => 'Souhaite loger au Domaine de Sarson',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Guest::class,
        ]);
    }
}
