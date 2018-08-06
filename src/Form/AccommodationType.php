<?php

namespace App\Form;

use App\Entity\Accommodation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AccommodationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => "Nom de l'hébergement",
                'attr' => ['placeholder' => "Ex : Domaine de Sarson"]      
            ])
            ->add('url', null, [
                'required' => false,
                'label' => "Lien vers le site web de l'hébergement",
                'attr' => ['placeholder' => "Ex : http://www.monsite.fr"]               
            ])
            ->add('address', null, [
                'label' => "Adresse",
                'attr' => ['rows' => 4]               
            ])
            ->add('details', null, [
                'label' => "Détails du lieu et de la prestation",
                'attr' => ['rows' => 8]               
            ])
            ->add('pricing', null, [
                'required' => false,
                'label' => "Détails du tarif",
                'attr' => ['rows' => 4, 'placeholder' => "Ex : 60€ par nuit + 10€ par personne pour le petit-déjeuner"]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => true,
                'allow_delete' => true,
                'download_label' => true,
                'download_uri' => true,
                'image_uri' => true,
                'label' => "Image d'illustration"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Accommodation::class,
        ]);
    }
}
