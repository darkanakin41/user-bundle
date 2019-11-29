<?php

namespace Darkanakin41\UserBundle\Form;

use Darkanakin41\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProfilForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', TextType::class, [
            'label' => 'firstname',
            'required' => TRUE,
        ]);
        $builder->add('lastname', TextType::class, [
            'label' => 'lastname',
            'required' => TRUE,
        ]);
        $builder->add("email", EmailType::class,[
            'label' => 'email',
        ]);
        $builder->add('date_naissance', DateType::class, [
            'label' => 'date_naissance',
            'required' => TRUE,
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'data-inputmask' => "'alias': 'dd/mm/yyyy'",
                'data-mask' => "data-provider='datepicker'",
                'data-date-format' => "dd/mm/yyyy",
                'placeholder' => 'date_naissance',
            ]
        ]);
        $builder->add('twitter', TextType::class, array(
            'label' => 'twitter',
            'required' => FALSE,
        ));
        $builder->add('facebook', TextType::class, array(
            'label' => 'facebook',
            'required' => FALSE,
        ));
        $builder->add('instagram', TextType::class, array(
            'label' => 'instagram',
            'required' => FALSE,
        ));
        $builder->add('twitch', TextType::class, array(
            'label' => 'twitch',
            'required' => FALSE,
        ));
        $builder->add('youtube', TextType::class, array(
            'label' => 'youtube',
            'required' => FALSE,
        ));
        $builder->add('avatarFile', FileType::class, array(
            'label' => 'avatar',
            'required' => FALSE,
            'mapped' => FALSE,
            'constraints' => array(
                new Image(array(
                    'allowLandscape' => FALSE,
                    'allowSquare' => TRUE,
                    "detectCorrupted" => TRUE,
                    "allowSquareMessage" => "Votre image est carré ({{width}}x{{height}}px). Nous n'acceptons que les images Portrait",
                    "allowLandscapeMessage" => "Votre image est au format paysage ({{width}}x{{height}}px). Nous n'acceptons que les images Portrait",
                )),
            )
        ));
        $builder->add("password", PasswordType::class, [
            'label' => 'password_confirm',
            'mapped' => false,
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'action.validate',
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
        return 'darkanakin41_user_bundle_profil_form';
    }
}
