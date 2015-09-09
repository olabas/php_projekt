<?php

/**
 * Register form.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */
namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegisterForm.
 *
 * @package Form
 * @extends AbstractType
 * @use Symfony\Component\Form\AbstractType
 * @use Symfony\Component\Form\FormBuilderInterface
 * @use Symfony\Component\OptionsResolver\OptionsResolverInterface
 * @use Symfony\Component\Validator\Constraints as Assert
 */
class RegisterForm extends AbstractType
{
    /**
     * Form builder.
     *
     * @access public
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return  $builder->add(
            'login',
            'text',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 2, 'max' => 16))
                ),
                'attr' => array(
                    'class' => 'form-control parts'
                )
            )
        )
        ->add(
            'password',
            'password',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 8))
                ),
                'attr' => array(
                    'class' => 'form-control parts'
                )
            )
        )
        ->add(
            'password_repeated',
            'password',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 8))
                ),
                'attr' => array(
                    'class' => 'form-control parts'
                )
            )
        )
        ->add(
            'name',
            'text',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 3))
                ),
                'attr' => array(
                    'class' => 'form-control parts'
                )
            )
        )
        ->add(
            'surname',
            'text',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 2))
                ),
                'attr' => array(
                    'class' => 'form-control parts'
                )
            )
        )
        ->add(
            'email',
            'text',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 8))
                ),
                'attr' => array(
                    'class' => 'form-control parts'
                )
            )
        )
        ->add(
            'phone_number',
            'text',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 9))
                ),
                'attr' => array(
                    'class' => 'form-control parts'
                )
            )
        )
        ->add(
            'sex',
            'choice',
            array(
                'constraints' => array(
                    new Assert\NotBlank()
                ),
                'attr' => array(
                    'class' => 'form-control parts'
                ),
                'choices' => array(
                    'f' => 'Female',
                    'm' => 'Male'
                )
            )
        );
    }

    /**
     * Gets form name.
     *
     * @access public
     *
     * @return string
     */
    public function getName()
    {
        return 'registerForm';
    }
}
