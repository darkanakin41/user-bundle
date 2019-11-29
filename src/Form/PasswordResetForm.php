<?php

namespace Darkanakin41\UserBundle\Form;

use Darkanakin41\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordResetForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("password", RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => ['label' => 'new_password'],
            'second_options' => ['label' => 'new_password_repeat']
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'action.change_password',
            'attr' => array(
                'class' => 'button custom'
            )
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'Darkanakin41User',
        ));
    }

    public function getBlockPrefix()
    {
        return 'darkanakin41_user_bundle_password_reset_form';
    }
}
