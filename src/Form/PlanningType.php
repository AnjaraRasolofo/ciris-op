<?php

namespace App\Form;

use App\Entity\Operateur;
use App\Entity\Planning;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PlanningType extends AbstractType
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
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Jour' => 'jour',
                    'Nuit' => 'nuit',
                    'Week-end' => 'weekend',
                    'Congé' => 'conge'
                ],
                'placeholder' => 'Sélectionner le type de planning',
                'required' => 'false',
            ])
            ->add('operateur', EntityType::class, [
                'class' => Operateur::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}
