<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

class PurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('share', RangeType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'type' => 'range',
                    'class' => 'form-range',
                    'max' => $options['max'],
                    'min' => 5,
                    'step' => 5
                ],
                'data' => $options['max']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
            'max' => 100
        ]);
    }
}
