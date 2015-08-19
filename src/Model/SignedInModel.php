<?php
    /**
     * Albums model.
     *
     * @author EPI <epi@uj.edu.pl>
     * @link http://epi.uj.edu.pl
     * @copyright 2015 EPI
     */

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;


    /**
     * Class AlbumsModel.
     *
     * @category Epi
     * @package Model
     * @use Silex\Application
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

    public function getOnlineUsername()
    {
        $token = $this->security->getToken();
        if (null !== $token) {
            $user = $token->getUsername();
        }
        return isset($user) ? $user : array();
    }

    public function getUser()
    {
        $login = $this -> getOnlineUsername();
        $query = 'SELECT * FROM users WHERE login = :login';
        $statement = $this->app['db']->prepare($query);
        $statement->bindValue('login', $login, \PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return !$result ? array() : $result;
    }
    public function getUsersOffers()
    {
        $login = $this -> getOnlineUsername();
        $query = 'Select * from users inner join posts on (users.id=posts.user_id) inner join states on (states.id=posts.state_id) inner join cities on (cities.id=posts.city_id) inner join categories on (categories.id=posts.category_id) where login = :login';
        $statement = $this->app['db']->prepare($query);
        $statement->bindValue('login', $login, \PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return !$result ? array() : $result;
    }

}