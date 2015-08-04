<?php
/**
 * Albums model.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Model;

use Silex\Application;

/**
 * Class AlbumsModel.
 *
 * @category Epi
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
     * Gets all albums.
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

    public function getAllStates()
    {
        $query = 'SELECT id, state FROM states';
        $result = $this->db->fetchAll($query);
        return !$result ? array() : $result;
    }

}