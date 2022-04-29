<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_sortie')
            ->add('date_debut')
            ->add('duree')
            ->add('date_limite_inscription')
            ->add('nb_inscription_max')
            ->add('description_sortie')
            ->add('motif_annulation')
            ->add('photo_sortie')
            ->add('site_organisateur')
            ->add('etat')
            ->add('lieu')
            ->add('organisateur')
            ->add('inscrits')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
