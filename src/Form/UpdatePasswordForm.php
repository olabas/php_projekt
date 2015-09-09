<?php

/**
 * Update password form.
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
 * Class UpdatePasswordForm.
 *
 * @package Form
 * @extends AbstractType
 * @use Symfony\Component\Form\AbstractType
 * @use Symfony\Component\Form\FormBuilderInterface
 * @use Symfony\Component\OptionsResolver\OptionsResolverInterface
 * @use Symfony\Component\Validator\Constraints as Assert
 */
class UpdatePasswordForm extends AbstractType
{

     /**
     * App object.
     *
     * @access protected
     * @var Silex\Application $app
     */
    protected $app;

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
            'old_password',
            'password',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 8))
                ),
                'attr' => array(
                    'class' => 'form-control',
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
                    'class' => 'form-control',
                ),
                'label' => 'New password'
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
                    'class' => 'form-control',
                ),
                'label' => 'Repeat password'
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
        return 'updatePasswordForm';
    }
}
