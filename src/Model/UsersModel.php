<?php

/**
 * Users model.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class Users.
 *
 * @package Model
 * @use Silex\Application
 * @use Doctrine\DBAL\DBALException
 * @use Symfony\Component\Security\Core\Exception\UnsupportedUserException
 * @use Symfony\Component\Security\Core\Exception\UsernameNotFoundException
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

    /**
     * Trans
     *
     * @access protected
     * @var Silex\Provider\DoctrineServiceProvider $trans
     */
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
     * @return array
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
     * Gets user roles by User id.
     *
     * @access public
     * @param integer $userId User ID
     *
     * @return integer $roles Role
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

    /**
     * Add user.
     *
     * @param array $user User's data
     * @access public
     * @return mixed Result
     */
    public function addUser($user)
    {
        if ($this->existsUser($user['login'])) {
            throw new UsernameNotUniqueException(
                $this->trans->trans('Username')
                .$user['login']
                .' '
                .$this->trans->trans('is not unique')
                .'.'
            );
        }
        $this->db->insert('users', $user);
    }

    /**
     * Exists user.
     *
     * @param string $login User's login
     * @access public
     * @return Result
     */
    public function existsUser($login)
    {
        $query='SELECT * FROM `users` WHERE `users`.`login` LIKE :login';
        $statement=$this->db->prepare($query);
        $statement->bindValue('login', $login, \PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result && count($result);
    }

    /**
     * Get user.
     *
     * @param integer $id User's data
     * @access public
     * @return array Result
     */
    public function getUser($id)
    {
        if (($id != '') && ctype_digit((string)$id)) {
            $query = '
                SELECT
                    *
                FROM
                    users
                WHERE
                    id = :id
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
     * Get all users.
     *
     * @access public
     * @return array Result
     */

    public function getAll()
    {
        $query = '
            SELECT
                id, login, name, surname
            FROM
                users
            ';
        $result = $this->db->fetchAll($query);
        return !$result ? array() : $result;
    }
}
