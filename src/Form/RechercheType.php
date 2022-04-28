<?php

namespace App\Form;


use App\Entity\Site;
use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lieu',EntityType::class, [
        'required' => false,
        'label' => 'Site',
        'class' => Site::class,
        'placeholder' => false,
//        'query_builder' => function (SortieRepository $sr) {
//            return $sr->createQueryBuilder('site')
//                ->orderBy('site.nom_site', 'ASC');
//        },
        'choice_label' => 'nom_site',
    ])
            ->add('nom_sortie', TextType::class, [
                'required'=> false,
                'label' => 'Le nom de la sortie contient',
                'attr' => ['maxlength' => 30, 'placeHolder' => 'nom de la sortie', 'autocomplete' => 'off'],

            ])
            ->add('date_debut', DateType::class, [
                'required' => false,
                'label' => 'entre'
            ])
            //->add('duree')
            ->add('date_limite_inscription', DateType::class, [
                'required' => false,
                'label' => 'et'
            ])
//            ->add('nb_inscription_max')
//            ->add('description_sortie')
//            ->add('motif_annulation')
//            ->add('photo_sortie')
//            ->add('site_organisateur')
//            ->add('etat')

            ->add('organisateur', CheckboxType::class, [
                'required' => false,
                'label' => 'Sortie dont je suis l\'organisateur/trice'

            ])
//            ->add('inscrits', CheckboxType::class, [
//                'required' => false,
//                'label' => 'Sortie dont je suis inscrit/e'
//            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Recherche'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }

    public function transform($boolean): int
    {
        // transform the boolean to a string
        return $boolean ? 'true' : 'false';
    }

    /**
     * @param string $string
     *
     * @return bool
     */
    public function reverseTransform($int): bool
    {
        // transform the string back to a boolean
        return filter_var($int, FILTER_VALIDATE_BOOL);
    }

}
