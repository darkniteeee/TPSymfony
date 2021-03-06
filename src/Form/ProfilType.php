<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

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

        $builder->add('plainPassword', PasswordType::class, [
            'required' => true,
            'label' => 'Entrer mot de passe pour confirmation '

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
            ->add('photo_profil', FileType::class, [
                'label' => 'Photo (PNG, JPG, BMP)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'Merci de sélectionner un fichier image.',
                    ])
                ],
            ]);

        $builder
            ->add('submit', SubmitType::class, [
            'label' => 'Enregistrer',]);


 }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'date_class' => Participant::class,
        ]);
    }
}
