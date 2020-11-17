<?php

namespace App\Form;

use App\Entity\Operation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', NumberType::class, [
              'scale' => 2
            ])
            ->add('type', ChoiceType::class, [
              'choices'  => Operation::TYPES,
              'choice_label' => function ($choice, $key, $value) {
                return $value;
              },
            ])
            ->add('label')
            ->add('save', SubmitType::class, [
              'label' => 'Executer',
              'attr' => [
                "class" => "btn-info"
              ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
