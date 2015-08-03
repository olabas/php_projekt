<?php
/**
 * Log in form.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegisterForm.
 *
 * @category Epi
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
                    'class' => 'form-control'
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
                        'class' => 'form-control'
                    )
                )
            )

            ->add(
                'repeat_password',
                'password',
                array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array('min' => 8))
                    ),
                    'attr' => array(
                        'class' => 'form-control'
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
                        'class' => 'form-control'
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
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'address',
                'text',
                array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array('min' => 8))
                    ),
                    'attr' => array(
                        'class' => 'form-control'
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
                        'class' => 'form-control'
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
                        'class' => 'form-control'
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