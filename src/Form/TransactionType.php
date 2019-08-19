<?php

namespace App\Form;

use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomenvoi')
            ->add('prenomenvoi')
            ->add('telephoneenvoi')
            ->add('CNIenvoi')
            ->add('nomretrai')
            ->add('telephoneretrai')
            ->add('CNIretrai')
            ->add('montantenvoi')
            ->add('commissionEtat')
            ->add('commissionAdmin')
            ->add('commissionEnvoi')
            ->add('commissionRetrait')
            ->add('statut')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
