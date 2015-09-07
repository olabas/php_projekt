<?php

/**
 * Posts form.
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
use Model\FiltersModel;
use Silex\Application;

/**
 * Class PostsForm.
 *
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

    /**
     * Object constructor.
     *
     * @access public
     * @param Silex\Application $app Silex application
     */
    public function __construct(Application $app)
    {
        $this->app=$app;
    }

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
                    new Assert\Length(array('min' => 5, 'max' => 45))
                ),
                'attr' => array(
                    'class' => 'form-control parts'
                ),
                'label' => 'Tytuł'
            )
        )
        ->add(
            'content',
            'textarea',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array('min' => 5))
                ),
                'attr' => array(
                    'class' => 'form-control textarea',
                ),
                'label' => 'Treść'
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
                ),
                'label' => 'Cena'
            )
        )
        ->add(
            'category_id',
            'choice',
            array(
                'attr' => array(
                    'class' => 'form-control small-parts',
                ),
                'choices' => $this->getCategory($this->app),
                'label' => 'Kategoria'
            )
        )
        ->add(
            'city_id',
            'choice',
            array(
                'attr' => array(
                    'class' => 'form-control small-parts',
                ),
                'choices' => $this->getCity($this->app),
                'label' => 'Lokalizacja'
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

    /**
     * Gets category name.
     *
     * @access private
     * @param Silex\Application $app Silex application
     *
     * @return array
     */
    private function getCategory($app)
    {
        $categoryModel = new FiltersModel($app);
        $data = $categoryModel->getAllCategories();
        $tab = $categoryModel->choiceCategory($data);
        return isset($tab) ? $tab : array();
    }

     /**
     * Gets city name.
     *
     * @access private
     * @param Silex\Application $app Silex application
     *
     * @return array
     */
    private function getCity($app)
    {
        $cityModel = new FiltersModel($app);
        $data = $cityModel->getAllCities();
        $tab = $cityModel->choiceCity($data);
        return isset($tab) ? $tab : array();
    }
}
