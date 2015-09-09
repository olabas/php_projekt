<?php

/**
 * Filters form.
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
 * Class FiltersForm.
 *
 * @package Form
 * @extends AbstractType
 * @use Symfony\Component\Form\AbstractType
 * @use Symfony\Component\Form\FormBuilderInterface
 * @use Symfony\Component\OptionsResolver\OptionsResolverInterface
 * @use Symfony\Component\Validator\Constraints as Assert
 * @use Model\FiltersModel;
 * @use Silex\Application;
 */
class FiltersForm extends AbstractType
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
            'category_id',
            'choice',
            array(
                'required' => false,
                'attr' => array(
                    'class' => 'form-control small-parts',
                ),
                'placeholder' => 'Category',
                'choices' => $this->getCategory($this->app),
                'label' => false
            )
        )
        ->add(
            'city_id',
            'choice',
            array(
                'required' => false,
                'attr' => array(
                    'class' => 'form-control small-parts',
                ),
                'placeholder' => 'Locality',
                'choices' => $this->getCity($this->app),
                'label' => false
            )
        )
        ->add(
            'sex',
            'choice',
            array(
                'required' => false,
                'attr' => array(
                    'class' => 'form-control small-parts',
                ),
                'placeholder' => 'Sex',
                'choices' => array(
                    'f' => 'Female',
                    'm' => 'Male'
                ),
                'label' => false
            )
        )
        ->add(
            'price',
            'choice',
            array(
                'required' => false,
                'attr' => array(
                    'class' => 'form-control small-parts',
                ),
                'placeholder' => 'Price',
                'label' => false,
                'choices' => array(
                    '10-20' => '10-20',
                    '20-30' => '20-30',
                    '30-40' => '30-40',
                    '40-50' => '40-50',
                    'powyżej 50' => 'powyżej 50'
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

    /**
     * Gets category name.
     *
     * @access public
     * @param Silex\Application $app Silex application
     *
     * @return array
     */
    public function getCategory($app)
    {
        $categoryModel = new FiltersModel($app);
        $data = $categoryModel->getAllCategories();
        $tab = $categoryModel->choiceCategory($data);
        return isset($tab) ? $tab : array();
    }

     /**
     * Gets city name.
     *
     * @access public
     * @param Silex\Application $app Silex application
     *
     * @return array
     */
    public function getCity($app)
    {
        $cityModel = new FiltersModel($app);
        $data = $cityModel->getAllCities();
        $tab = $cityModel->choiceCity($data);
        return isset($tab) ? $tab : array();
    }
}
