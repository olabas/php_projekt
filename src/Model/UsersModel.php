<?php
/**
 * Users model.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class Users.
 *
 * @category Epi
 * @package Model
 * @use Silex\Application
 */
class UsersModel
{
    /**
     * Db object.
     *
     * @access protected
     * @var Silex\Provider\DoctrineServiceProvider $db
     */
    protected $db;

    protected $trans;

    /**
     * Object constructor.
     *
     * @access public
     * @param Silex\Application $app Silex application
     */
    public function __construct(Application $app)
    {
        $this->db = $app['db'];
        $this->trans = $app['translator'];
    }

    /**
     * Loads user by login.
     *
     * @access public
     * @param string $login User login
     * @throws UsernameNotFoundException
     * @return array Result
     */
    public function loadUserByLogin($login)
    {
        $user = $this->getUserByLogin($login);

        if (!$user || !count($user)) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $login)
            );
        }

        $roles = $this->getUserRoles($user['id']);

        if (!$roles || !count($roles)) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $login)
            );
        }

        return array(
            'login' => $user['login'],
            'password' => $user['password'],
            'name' => $user['name'],
            'surname' => $user['surname'],
            'address' => $user['address'],
            'email' => $user['email'],
            'phone_number' => $user['phone_number'],
            'roles' => $roles
        );

    }

    /**
     * Gets user data by login.
     *
     * @access public
     * @param string $login User login
     *
     * @return array Result
     */
    public function getUserByLogin($login)
    {
        try {
            $query = '
              SELECT
                `id`, `login`, `password`, `role_id`, `name`, `surname`, `address`, `email`, `phone_number`
              FROM
                `users`
              WHERE
                `login` = :login
            ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('login', $login, \PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return !$result ? array() : current($result);
        } catch (\PDOException $e) {
            return array();
        }
    }

    /**
     * Gets user roles by User ID.
     *
     * @access public
     * @param integer $userId User ID
     *
     * @return array Result
     */
    public function getUserRoles($userId)
    {
        $roles = array();
        try {
            $query = '
                SELECT
                    `roles`.`name` as `role`
                FROM
                    `users`
                INNER JOIN
                    `roles`
                ON `users`.`role_id` = `roles`.`id`
                WHERE
                    `users`.`id` = :user_id
                ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('user_id', $userId, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            if ($result && count($result)) {
                $result = current($result);
                $roles[] = $result['role'];
            }
            return $roles;
        } catch (\PDOException $e) {
            return $roles;
        }
    }

     public function addUser($user)
     {
         var_dump($user);
         if($this->existsUser($user['login'])){
            throw new UsernameNotUniqueException(
                $this->trans->trans('Username')
                .$user['login']
                .' '
                .$this->trans->trans('is not unique')
                .'.');
         }
         $this->db->insert('users', $user);
     }

    public function existsUser($login)
    {
        $query='SELECT * FROM `users` WHERE `users`.`login` LIKE :login';
        $statement=$this->db->prepare($query);
        $statement->bindValue('login', $login, \PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result && count($result);

    }
}