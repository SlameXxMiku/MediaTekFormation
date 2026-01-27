<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', null, [
                'label' => 'Date de publication',
            ])

            ->add('title', null, [
                'label' => 'Titre',
            ])

            ->add('description', null, [
                'label'    => 'Description',
                'required' => false,
            ])

            ->add('miniature', null, [
                'label'    => 'Miniature (URL)',
                'required' => false,
            ])

            ->add('picture', null, [
                'label'    => 'Image (URL)',
                'required' => false,
            ])

            ->add('videoId', null, [
                'label'    => 'ID YouTube',
                'required' => false,
            ])

            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'name',
                'label' => 'Playlist',
                'placeholder' => 'Sélectionner une playlist',
            ])

            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'label' => 'Catégories',
                'multiple' => true,
                'required' => false,
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
