<?php

/**
 * User provider.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */


namespace Provider;

use Model\User;
use Silex\Application;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Model\UsersModel;

/**
 * Class UserProvider.
 *
 * @package Provider
 * @use Silex\Application
 * @use Symfony\Component\Security\Core\User\UserProviderInterface
 * @use Symfony\Component\Security\Core\User\UserInterface
 * @use Symfony\Component\Security\Core\User\User
 * @use Symfony\Component\Security\Core\Exception\UsernameNotFoundException
 * @use Symfony\Component\Security\Core\Exception\UnsupportedUserException
 * @use Model\UsersModel
 */
class UserProvider implements UserProviderInterface
{
    /**
     * Silex application.
     *
     * @access protected
     * @var Silex\Application $app
     */
    protected $app;

    /**
     * Object constructor.
     *
     * @access public
     * @param Silex\Application $app Silex application
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Load user by username.
     *
     * @access public
     * @param string $login User login
     *
     * @return User Result
     */
    public function loadUserByUsername($login)
    {
        $userModel = new UsersModel($this->app);
        $user = $userModel->loadUserByLogin($login);
        return new User(
            $user['login'],
            $user['password'],
            $user['name'],
            $user['surname'],
            $user['address'],
            $user['email'],
            $user['phone_number'],
            $user['roles'],
            true,
            true,
            true,
            true
        );
    }

    /**
     * Refresh user.
     *
     * @access public
     * @param UserInterface $user User
     *
     * @return User Result
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    get_class($user)
                )
            );
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Check if supports selected class.
     *
     * @access public
     * @param string $class Class name
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'Model/User';
    }
}
