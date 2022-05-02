<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('nom_sortie', TextType::class, [
            'label' => 'Nom de la sortie'
        ]);
        $builder->add('date_debut', DateTimeType::class, [
            'label' => 'Date et heure'
        ]);
        $builder->add('duree', NumberType::class, [
            'label' => 'Durée de la sortie (en minutes)'
        ]);
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

        $builder->add('ville', EntityType::class, [
            'label' => 'Ville',
            'mapped' => false,
            'class' => Ville::class,
            'placeholder' => 'Sélectionner une ville',
            'choice_label' => 'nom_ville',

        ]);
        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addLieuField($form->getParent(), $form->getData());
            }
        );
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                $lieu = $data->getLieu();
                $form = $event->getForm();
                if ($lieu) {
                    $ville = $lieu->getVille();
                    $this->addLieuField($form, $ville);
                    $form->get('ville')->setData($ville);
                } else {
                    $this->addLieuField($form, null);
                }
            }
        );

    }

    private function addLieuField(FormInterface $form, ?Ville $ville)
        {
            $builder = $form->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'placeholder' => $ville ? 'Sélectionnez votre lieu' : 'Selectionnez votre ville',
                'choice_label' => 'nom_lieu',
                'required' => true,
                'auto_initialize' => false,
                'choices' => $ville ? $ville->getLieux() : []
            ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
