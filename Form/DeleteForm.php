<?php

namespace PLejeune\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('delete', SubmitType::class, array(
            "label" => "action.delete.yes",
            'attr' => array(
                'class' => "button success",
            ),
        ));
        $builder->add('cancel', SubmitType::class, array(
            'label' => 'action.delete.no',
            'attr' => array(
                'class' => "button",
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'PLejeuneUser',
        ));
    }

    public function getName()
    {
        return 'plejeune_user_bundle_delete_form';
    }

}
