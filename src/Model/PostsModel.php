<?php

/**
 * Posts model.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
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
        $query = '
            SELECT 
                    posts.id, cities.city, categories.category, 
				posts.title, posts.content, 
				posts.post_date, posts.price, 
				users.login
            FROM 
                posts
            INNER JOIN 
                users 
            ON 
                (users.id=posts.user_id) 
          
            INNER JOIN 
                cities 
            ON 
                (cities.id=posts.city_id) 
            INNER JOIN 
                categories 
            ON 
                (categories.id=posts.category_id)
            ';
        $result = $this->db->fetchAll($query);
        return !$result ? array() : $result;
    }

    public function getPost($id)
    {
        if (($id != '') && ctype_digit((string)$id)) {
            $query = '
                SELECT 
                    posts.id, cities.city, categories.category, 
                posts.title, posts.content, 
                posts.post_date, posts.price, posts.user_id,
                users.login, users.name, users.surname, users.email, 
                users.phone_number 
                FROM
                    posts
                INNER JOIN
                    categories
                ON
                    categories.id = posts.category_id
                INNER JOIN
                    cities
                ON
                    cities.id = posts.city_id
                INNER JOIN
                    users
                ON
                    users.id = posts.user_id
                WHERE
                    posts.id = :id
                ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('id', $id, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return !$result ? array() : current($result);
        } else {
            return array();
        }
    }

    /**
    * Save album.
    *
    * @access public
    * @param array $album Album data
    * @retun mixed Result
    */
    public function addPost($post)
    {
        return $this->db->insert('posts', $post);
    }

    public function updatePost($post, $id)
    {
        var_dump($post);
        return $this->db->update('posts', $post, array('id' => $id));
    }
}
