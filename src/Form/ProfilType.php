<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'required' => true]);

        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true]);

        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => true]);

        $builder
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'required' => true]);

        $builder
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true]);

        $builder
            ->add('password', TextType::class, [
                'label' => 'Mot de passe',
                'required' => true]);

        $builder
            ->add('confirmation', TextType::class, [
                'label' => 'Confirmation',
                'required' => true]);

        $builder
            ->add('nom_site', EntityType::class, [
                'label' => 'Site de rattachement',
                'required' => true,
                'class' => Site::class,
                'query_builder' => function (SiteRepository $sr) {
                    return $sr->createQueryBuilder('site')
                        ->orderBy('site.nom_site', 'ASC');},
                'choice_label' => 'site']);

        $builder
            ->add('photo_profil', TextType::class, [
                'label' => 'Ma photo']);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Modifier',
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'date_class' => Participant::class,
            'type' => 'modifier',
            ]);
    }
}
