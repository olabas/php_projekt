<?php

/**
 * Signed in model.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;

    /**
     * Class SignedInModel.
     *
     * @package Model
     * @use Silex\Application
     * @use Doctrine\DBAL\DBALException;
     */
class SignedInModel
{
    /**
     * Db object.
     *
     * @access protected
     * @var Silex\Provider\DoctrineServiceProvider $db
     */
    protected $db;

    protected $security;

    /**
     * Object constructor.
     *
     * @access public
     * @param Silex\Application $app Silex application
     */
    public function __construct(Application $app)
    {
        $this->db = $app['db'];
        $this->security = $app['security'];
        $this->app = $app;
    }

    /**
     * Gets online username.
     *
     * @access public
     * @return array User
     */
    public function getOnlineUsername()
    {
        $token = $this->security->getToken();
        if (null !== $token) {
            $user = $token->getUsername();
        }
        return isset($user) ? $user : array();
    }

    /**
     * Gets user
     *
     * @access public
     * @return array Result
     */
    public function getUser()
    {
        $login = $this -> getOnlineUsername();
        $query = 'SELECT id, login, password, name, surname, email, phone_number FROM users WHERE login = :login';
        $statement = $this->app['db']->prepare($query);
        $statement->bindValue('login', $login, \PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return !$result ? array() : current($result);
    }

    /**
     * Gets user's offers.
     *
     * @access public
     * @return array Result
     */
    public function getUsersOffers()
    {
        $login = $this -> getOnlineUsername();
        $query = '
            SELECT 
                posts.id, cities.city, categories.category, 
                posts.title, posts.content, 
                posts.post_date, posts.price, 
                users.login 
            FROM 
                users 
            INNER JOIN 
                posts 
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
            WHERE 
                login = :login
            ';
        $statement = $this->app['db']->prepare($query);
        $statement->bindValue('login', $login, \PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return !$result ? array() : $result;
    }

    /**
     * Gets offer.
     *
     * @access public
     * @param integer $id User's id
     * @return array Result
     */
    public function getOffer($id)
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

        public function updateProfile($user, $id)
    {
        var_dump($user);
        return $this->db->update('users', $user, array('id' => $id));
    }
}
