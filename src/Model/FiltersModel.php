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
     * @param array $categories Category's name
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
     * @param array $cities City's name
     * @return array
     */
    public function choiceCity($cities)
    {
        foreach ($cities as $city) {
            $data[(int)$city['id']] = (string)$city['city'];
        }
        return isset($data) ? $data : array();
    }

    /**
     * Prepare filters.
     *
     * @access public
     * @param array $filters Filters data
     * @return array
     */
    public function prepareFilters($filters)
    {
        foreach ($filters as $key => &$filter) {
            if ($filter != null) {
                if ($key == 'price') {
                    if ($filter == 'powyżej 50') {
                        $filters['from'] = 50;
                        $filters['to'] = 10000;
                    } else {
                        $filters['from'] = (int)substr($filter, 0, 2);
                        $filters['to'] = (int)substr($filter, 3, 4);
                    }
                    unset($filters['price']);
                } else {
                	$filter = (string)$filter;
                }
            } else {
                if ($key == 'price') {
                    $filters['from'] = 0;
                    $filters['to'] = 10000;
                    unset($filters['price']);
                } else {
                    $filter = '%';
                }
            }
        }
        return $filters;
    }

    public function bindValues($statement, $data)
    {
        $i = 1;
        foreach ($data as $value) {
            if (is_int($value)) {
                $statement->bindValue($i, $value, \PDO::PARAM_INT);
            } elseif (is_string($value)) {
                $statement->bindValue($i, $value, \PDO::PARAM_STR);
            }
            $i++;
        }
        return true;
    }

    /**
     * Filter Posts
     *
     * @access public
     * @param array $filters Filters data
     * @return array Result
     */
    public function filterPosts($filters)
    {
        $data = $this->prepareFilters($filters);
        var_dump($data);
        $query = '
            SELECT
            	posts.id, cities.city, categories.category,
            	posts.price, posts.content, users.login as author,
            	posts.title
            FROM
            	posts
            RIGHT JOIN
            	cities
            ON
            	posts.city_id = cities.id
            RIGHT JOIN
            	categories
            ON
            	posts.category_id = categories.id
            RIGHT JOIN 
            	users
            ON
            	posts.user_id = users.id
            WHERE (
            		categories.id LIKE ?
            	AND
            		cities.id LIKE ?
            	AND
            		users.sex LIKE ?
            	AND
            		posts.price BETWEEN ? AND ?
            )
            ';
        $statement = $this->db->prepare($query);
        $this->bindValues($statement, $data);
        $statement->execute();
        $result = $this->db->fetchAll($query);
        return !$result ? array() : $result;
    }
}
