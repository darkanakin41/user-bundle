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

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', TextType::class, [
            'label' => "firstname",
            'required' => TRUE,
        ]);
        $builder->add('lastname', TextType::class, [
            'label' => "lastname",
            'required' => TRUE,
        ]);
        $builder->add('date_naissance', DateType::class, [
            'label' => "date_naissance",
            'required' => TRUE,
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'data-inputmask' => "'alias': 'dd/mm/yyyy'",
                'data-mask' => "data-provider='datepicker'",
                'data-date-format' => "dd/mm/yyyy",
                'placeholder' => 'Date de naissance',
            ]
        ]);
        $builder->add("email", EmailType::class);
        $builder->add("username", TextType::class);
        $builder->add("password", RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => ['label' => 'password'],
            'second_options' => ['label' => 'password_repeat']
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'action.register',
            'attr' => array(
                'class' => 'button custom'
            )
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'translation_domain' => 'Darkanakin41User',
        ));
    }

    public function getBlockPrefix()
    {
        return 'darkanakin41_user_bundle_registration_form';
    }
}
