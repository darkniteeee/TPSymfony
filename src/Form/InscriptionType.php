<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',]);

        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom']);

        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom']);

        $builder
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'required' =>false]);

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email']);

        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'invalid_message' => 'Les mots de passe ne sont pas identiques !',
            'options' => ['attr' => ['class' => 'password-field']],
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Mot de passe (Confirmation)'],
        ]);

        $builder
            ->add('site', EntityType::class, [
                'label' => 'Site de rattachement',
                'mapped' =>false,
                'class' => Site::class,
                'query_builder' => function (SiteRepository $sr)
        {
                    return $sr->createQueryBuilder('site')
                        ->orderBy('site.nom_site', 'ASC');},
                'choice_label' => 'nom_site']);

        $builder
            ->add('administrateur', CheckboxType::class ,[
               'label' => 'administrateur',
                'required' => false,
            ]);


        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Inscrire']);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'date_class' => Participant::class,
        ]);
    }
}
