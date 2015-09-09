<?php

/**
 * Register controller.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */

namespace Controller;

use Model\UsernameNotUniqueException;
use Model\UsersModel;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Form\RegisterForm;

/**
 * Class RegisterController.
 *
 * @package Controller
 * @extends BaseController
 * @implements ControllerProviderInterface
 * @use Model\UsernameNotUniqueException;
 * @use Model\UsersModel;
 * @use Silex\Application;
 * @use Silex\ControllerProviderInterface;
 * @use Symfony\Component\HttpFoundation\Request;
 * @use Form\RegisterForm;
 */
class RegisterController extends BaseController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return RegisterController Result
     */
    public function connect(Application $app)
    {
        $registerController = $app['controllers_factory'];
        $registerController->post('register', array($this, 'registerAction'));
        $registerController->match('register', array($this, 'registerAction'))
            ->bind('register_register');
        return $registerController;
    }

    /**
     * Register action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function registerAction(Application $app, Request $request)
    {
        try {
            $user = array(
                'login' => $app['session']->get('_security.last_username')
            );

            $error = $app['security.last_error']($request);

            $form = $app['form.factory']->createBuilder(new RegisterForm(), $user)
                ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data=$form->getData();
                if ($data['password']!=$data['password_repeated']) {
                    $error = $app['translator']->trans('Passwords do not match').'.';
                } else {
                    $model=new UsersModel($app);

                    try {
                        $model->addUser(array(
                            'login' => $data['login'],
                            'password' => $app['security.encoder.digest']->encodePassword($data['password'], ''),
                            'name' => $data['name'],
                            'surname' => $data['surname'],
                            'email' => $data['email'],
                            'phone_number' => $data['phone_number'],
                            'sex' => $data['sex'],
                            'role_id' => 2
                        ));
                        $app['session']->getFlashBag()->add(
                            'message',
                            array(
                                'type' => 'success',
                                'content' => $app['translator']->trans('Account has been created. You can log in now.')
                            )
                        );
                        return $app->redirect(
                            $app['url_generator']->generate(
                                'index'
                            ),
                            301
                        );
                        $view['form']=$form->createView();
                        return $app['twig']->render('register/register.twig', $view);
                    } catch (UsernameNotUniqueException $e) {
                        $app['session']->getFlashBag()->add(
                            'message',
                            array(
                                'type' => 'danger',
                                'content' => $app['translator']->trans(
                                    'Username is not unique. Please choose another one and try again.'
                                )
                            )
                        );
                    }
                }
            }
            $view = parent::getView();
            $view['form']=$form->createView();
            //$view['error']=$error;
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
        return $app['twig']->render('register/register.twig', $view);
    }
}
