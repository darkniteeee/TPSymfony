<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifierPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('password', PasswordType::class, [
            'required' => true,
            'label' => 'Mot de passe actuel',
        ]);

        $builder->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true,
            'invalid_message' => 'Les mots de passe ne sont pas identiques !',
            'options' => ['attr' => ['class' => 'password-field']],
            'first_options' => ['label' => 'Nouveau mot de passe'],
            'second_options' => ['label' => 'Nouveau mot de passe (Confirmation)'],]);

        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'date_class' => Participant::class,
        ]);
    }
}