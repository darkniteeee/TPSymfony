<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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

        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'invalid_message' => 'Les mots de passe ne sont pas identiques !',
            'options' => ['attr' => ['class' => 'password-field']],
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Mot de passe (Confirmation)'],
        ]);

        $builder
            ->add('site_id', EntityType::class, [
                'label' => 'Site de rattachement',
                'required' => true,
                'class' => Site::class,
                'query_builder' => function (SiteRepository $sr) {
                    return $sr->createQueryBuilder('site')
                        ->orderBy('site.nom_site', 'ASC');},
                'choice_label' => 'nom_site']);

//        $builder
//            ->add('photo_profil', FileType::class, [
//                'label' => 'Ma photo',
//                'mapped' => false,
//                'required' => false,
//                'constraints' => [
//                    new File([
//                        'maxSize' => '1024k',
//                        'mimeTypes' => ['image/jpeg', 'image/png'],
//                        'mimeTypesMessage' => 'Merci de télécharger un fichier jpeg ou png valide',])
//                    ]
//            ]);

        $builder
            ->add('submit', SubmitType::class, [
            'label' => 'Enregistrer',]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'date_class' => Participant::class,
            'type' => 'modifier',
            ]);
    }
}
