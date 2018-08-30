<?php

namespace App\Form;

use App\Entity\ListItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ListItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => "Titre de l'élément"
            ])
            ->add('description', null, [
                'label' => "Description"
            ])
            ->add('price', null, [
                'label' => "Prix (en €)"
            ])
            ->add('link', null, [
                'label' => "Lien vers le site web",
                'attr' => ['placeholder' => "http://"]
            ])
            ->add('splittable', null, [
                'label' => "Les invités ont-ils la possibilité de ne financer qu'une partie de ce cadeau ?"
            ])
            ->add('ordering', null, [
                'label' => "Ordre dans la liste"
            ])
            ->add('latitude', null, [
                'attr' => ['placeholder' => "Latitude - Ex : \"45.501690\""],
                'help' => "Aide : Aller sur https://www.latlong.net"
            ])
            ->add('longitude', null, [
                'attr' => ['placeholder' => "Longitude - Ex : \"-73.567253\""]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => $options['new'],
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
            'data_class' => ListItem::class,
            'new' => true
        ]);
    }
}
