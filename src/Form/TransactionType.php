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
            ->add('adresseenvoi')
            ->add('dateenvoi')
            ->add('dateretrai')
            ->add('nomretrai')
            ->add('adresseretrai')
            ->add('telephoneretrai')
            ->add('CNIretrai')
            ->add('codeenvoi')
            ->add('montantenvoi')
            ->add('tarif')
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
