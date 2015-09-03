<?php

/**
 * Filters model.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */

namespace Model;

use Silex\Application;

/**
 * Class FiltersModel.
 *
 * @package Model
 * @use Silex\Application
 */
class FiltersModel
{
    /**
     * Db object.
     *
     * @access protected
     * @var Silex\Provider\DoctrineServiceProvider $db
     */
    protected $db;

    /**
     * Object constructor.
     *
     * @access public
     * @param Silex\Application $app Silex application
     */
    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    /**
     * Gets all categories.
     *
     * @access public
     * @return array Result
     */
    public function getAllCategories()
    {
        $query = 'SELECT id, category FROM categories';
        $result = $this->db->fetchAll($query);
        return !$result ? array() : $result;
    }

    /**
     * Gets all cities.
     *
     * @access public
     * @return array Result
     */
    public function getAllCities()
    {
        $query = 'SELECT id, city FROM cities';
        $result = $this->db->fetchAll($query);
        return !$result ? array() : $result;
    }

    /**
     * Choice category
     *
     * @access public
     * @param array $categories Categories name
     * @return array
     */
    public function choiceCategory($categories)
    {
        foreach ($categories as $category) {
            $data[(int)$category['id']] = (string)$category['category'];
        }
        return isset($data) ? $data : array();
    }

    /**
     * Choice city.
     *
     * @access public
     * @param array $cities Cities name
     * @return array
     */
    public function choiceCity($cities)
    {
        foreach ($cities as $city) {
            $data[(int)$city['id']] = (string)$city['city'];
        }
        return isset($data) ? $data : array();
    }
}
