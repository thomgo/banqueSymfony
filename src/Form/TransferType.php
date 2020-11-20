<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;


class TransferType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security) {
      $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debitAccount', EntityType::class, [
              "label" => "Compte à débiter",
              "class" => Account::class,
              "choice_label" => function($account) {
                return $account->getType() . " (" . $account->getAmount() . ")";
              },
              "choices" => $this->security->getUser()->getAccounts()
            ])
            ->add('creditAccount', EntityType::class, [
              "label" => "Compte à créditer",
              "class" => Account::class,
              "choice_label" => function($account) {
                return $account->getType() . " (" . $account->getAmount() . ")";
              },
              "choices" => $this->security->getUser()->getAccounts()
            ])
            ->add('amount', NumberType::class, [
              "label" => "Montant de la transaction",
              'scale' => 2
            ])
            ->add('label', TextType::class, [
              "label" => "Libellé de la transaction",
              "required" => false
            ])
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
            // Configure your form options here
        ]);
    }
}
