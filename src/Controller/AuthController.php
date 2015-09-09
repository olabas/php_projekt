<?php

/**
 * Auth controller.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
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
 * @extends BaseController
 * @implements ControllerProviderInterface
 * @use Silex\Application;
 * @use Silex\ControllerProviderInterface;
 * @use Symfony\Component\HttpFoundation\Request;
 * @use Form\LoginForm;
 */
class AuthController extends BaseController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return authController Result
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
        try {
            $user = array(
                'login' => $app['session']->get('_security.last_username')
            );

            $form = $app['form.factory']->createBuilder(new LoginForm(), $user)
                ->getForm();

            $view=parent::getView();
            $view['form']=$form->createView();
            $view['error']=$app['security.last_error']($request);
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
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
        try {
            $app['session']->clear();
            $app['session']->getFlashBag()->add(
                'message',
                array(
                    'type' => 'success',
                    'content' => $app['translator']->trans('You were safely log out.')
                )
            );
            return $app->redirect(
                $app['url_generator']->generate('index'),
                301
            );
        } catch (\PDOException $e) {
            $app->abort(
                404,
                $app['translator']->trans('Not found.')
            );
        }
        return $app['twig']->render('posts/index.twig', parent::getView());
    }
}
