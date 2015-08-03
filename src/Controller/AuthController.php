<?php
/**
 * Auth controller.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Form\LoginForm;

/**
 * Class AuthController.
 *
 * @package Controller
 * @implements ControllerProviderInterface
 */
class AuthController extends BaseController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return AlbumsController Result
     */
    public function connect(Application $app)
    {
        $authController = $app['controllers_factory'];
        $authController->match('login', array($this, 'loginAction'))
            ->bind('auth_login');
        $authController->get('logout', array($this, 'logoutAction'))
            ->bind('auth_logout');
        return $authController;
    }

    /**
     * Login action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function loginAction(Application $app, Request $request)
    {
        $user = array(
            'login' => $app['session']->get('_security.last_username')
        );

        $form = $app['form.factory']->createBuilder(new LoginForm(), $user)
            ->getForm();

        $view=parent::getView();
        $view['form']=$form->createView();
        $view['error']=$app['security.last_error']($request);

        return $app['twig']->render('auth/login.twig', $view);
    }

    /**
     * Logout action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function logoutAction(Application $app, Request $request)
    {
        $app['session']->clear();
        return $app['twig']->render('posts/index.twig', parent::getView());
    }
}