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
 * Class LoginForm.
 *
 * @category Epi
 * @package Form
 * @extends AbstractType
 * @use Symfony\Component\Form\AbstractType
 * @use Symfony\Component\Form\FormBuilderInterface
 * @use Symfony\Component\OptionsResolver\OptionsResolverInterface
 * @use Symfony\Component\Validator\Constraints as Assert
 */
class PostsForm extends AbstractType
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
            'title',
            'text',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 8, 'max' => 16))
                ),
                'attr' => array(
                    'class' => 'form-control'
                )
            )
        )
            ->add(
                'content',
                'textarea',
                array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array('min' => 8))
                    ),
                    'attr' => array(
                        'class' => 'form-control',
                        'cols' => '5',
                        'rows' => '5'
                    )
                )
            )

        ->add(
        'price',
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
    );
    }

    /**
     * Gets form name.
     *
     * @access public
     *
     * @return string
     */
    public function getPost()
    {
        return 'postsForm';
    }
}