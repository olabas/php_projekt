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
use Model\FiltersModel;
use Silex\Application;

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

    protected $app;

    public function __construct (Application $app)
    {
        $this->app=$app;
    }

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
                    'class' => 'form-control parts'
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
                        'class' => 'form-control textarea',
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
                'class' => 'form-control parts'
            )
        )
    )
            ->add(
                'category',
                'choice',
                array(
                    'attr' => array(
                        'class' => 'form-control small-parts',
                    )
                )
            )

            ->add(
                'state',
                'choice',
                array(
                    'attr' => array(
                        'class' => 'form-control small-parts',
                    )
                )
            )

            ->add(
                'city',
                'choice',
                array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                    'attr' => array(
                        'class' => 'form-control small-parts',
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
        return 'postsForm';
    }

    private function getCategories($app)
    {
        $categoryModel = new FiltersModel($app);
        $data = $categoryModel -> getAllCategories();
        $tab = $categoryModel -> choiceCategory();
    }
}