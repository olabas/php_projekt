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
    }

    public function getOnlineUsername()
    {
        $token = $this->security->getToken();
        if (null !== $token) {
            $user = $token->getUsername();
        }
        return isset($user) ? $user : array();
    }

    public function getUser($login)
    {
        $query = 'SELECT * FROM users WHERE login = :login';
        $result = $this->db->fetchAll($query);
        return !$result ? array() : $result;
    }
}