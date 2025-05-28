<?php

namespace App\Form;

use App\Entity\Conge;
use App\Entity\Operateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('debut', null, [
                'widget' => 'single_text',
            ])
            ->add('fin', null, [
                'widget' => 'single_text',
            ])
            ->add('motif')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'en attente' => 'en attente',
                    'approuvé' => 'approuvé',
                    'rejeté' => 'rejeté',
                ],
                'required' => 'true',
            ])
            ->add('operateur', EntityType::class, [
                'class' => Operateur::class,
                'choice_label' => 'prenom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conge::class,
        ]);
    }
}
