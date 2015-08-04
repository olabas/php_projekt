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
class PostsModel
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
    public function getAll()
    {
        $query = 'Select * from users inner join posts on (users.id=posts.user_id) inner join states on (states.id=posts.state_id) inner join cities on (cities.id=posts.city_id) inner join categories on (categories.id=posts.category_id)';
        $result = $this->db->fetchAll($query);
        return !$result ? array() : $result;
    }

}