<?php

namespace App\Form;

use App\Entity\UserGroupRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserGroupRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('receiver', EntityType::class, [
                'class' => User::class,
                'expanded' => false,
                'choices' => $options['friends'],
                'choice_label' => function($user) {
                    return $user->getUsername();
                },
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserGroupRequest::class,
            'friends' => []
        ]);
    }
}
