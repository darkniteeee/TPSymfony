<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('nom_sortie', TextType::class, [
            'label' => 'Nom de la sortie'
        ]);
        $builder->add('date_debut', DateType::class, [
            'label' => 'Date et heure'
        ]);
        $builder->add('duree');
        $builder->add('date_limite_inscription', DateType::class, [
            'label' => 'Date limite d"inscription'
        ]);
        $builder->add('nb_inscription_max', NumberType::class, [
            'label' => 'Nombre de places'
        ]);
        $builder->add('description_sortie', TextType::class, [
            'label' => 'Description et informations'
        ]);

//        $builder->add('motif_annulation');
//        $builder->add('photo_sortie');

        $builder->add('site_organisateur', EntityType::class, [
            'label' => 'Site Organisateur',
            'class' => Sortie::class,
//            'query_builder' => function (SortieRepository $sr) {
//                return $sr->createQueryBuilder('site')
//                    ->orderBy('site.nom_site', 'ASC');
//            },
        ]);

        $builder->add('lieu', EntityType::class, [
            'label' => 'Lieu',
            'class' => Lieu::class,
            'query_builder' => function (LieuRepository $lr) {
                return $lr->createQueryBuilder('lieu')
                    ->orderBy('lieu.nom_lieu', 'ASC');
            },
        ]);

        $builder->add('ville', EntityType::class, [
            'label' => 'Ville',
            'mapped' => false,
            'class' => Ville::class,
            'query_builder' => function (VilleRepository $lr) {
                return $lr->createQueryBuilder('ville')
                    ->orderBy('ville.nom_ville', 'ASC');
            },
        ]);

        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Créer']);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
