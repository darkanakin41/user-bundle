<?php

namespace Darkanakin41\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordResetRequestForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("username", TextType::class, [
            "required" => TRUE,
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'action.reset_password',
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

    public function getName()
    {
        return 'darkanakin41_user_bundle_password_reset_request_form';
    }

}
