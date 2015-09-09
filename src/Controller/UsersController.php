<?php

/**
 * User controller.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Model\SignedInModel;
use Form\RegisterForm;
use Form\UpdateProfileForm;
use Form\UpdatePasswordForm;

/**
 * Class UsersController.
 *
 * @package Controller
 * @extends BaseController
 * @implements ControllerProviderInterface
 * @use Silex\Application;
 * @use Silex\ControllerProviderInterface;
 * @use Symfony\Component\HttpFoundation\Request;
 * @use Symfony\Component\Validator\Constraints as Assert;
 * @use Model\SignedInModel;
 * @use Form\RegisterForm;
 * @use Form\UpdateProfileForm;
 * @use Form\UpdatePasswordForm;
 */
class UsersController extends BaseController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return UsersController Result
     */
    public function connect(Application $app)
    {
        $usersController = $app['controllers_factory'];
        $usersController->get('/', array($this, 'indexAction'))->bind('auth_data');
        $usersController->get('/data', array($this, 'indexAction'));
        $usersController->get('/data/', array($this, 'indexAction'));
        $usersController->get('/offers', array($this, 'indexOffersAction'))->bind('auth_myoffers');
        $usersController->get('/offers/', array($this, 'indexOffersAction'));
        $usersController->match('/view/{id}', array($this, 'viewOfferAction'))
            ->bind('offers_view');
        $usersController->match('/view/{id}/', array($this, 'viewOfferAction'));
         $usersController->match('/edit/{id}', array($this, 'editAction'));
        $usersController->match('/edit/{id}', array($this, 'editAction'))
            ->bind('users_edit');
        $usersController->match('/edit/{id}/', array($this, 'editAction'));
        $usersController->match('/password/{id}/', array($this, 'PasswordAction'));
        $usersController->match('/password/{id}', array($this, 'PasswordAction'))
            ->bind('password_edit');
        $usersController->post('/delete/{id}', array($this, 'deleteAction'));
        $usersController->match('/delete/{id}', array($this, 'deleteAction'))
            ->bind('users_delete');
        $usersController->match('/delete/{id}/', array($this, 'deleteAction'));


        return $usersController;
    }

    /**
     * Index action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function indexAction(Application $app, Request $request)
    {
        try {
            $view = parent::getView();
            $signedInModel = new SignedInModel($app);
            $view['user'] = $signedInModel->getUser();
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Album not found'));
        }
        return $app['twig']->render('auth/data.twig', $view);
    }

    /**
     * Edit action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function editAction(Application $app, Request $request)
    {
        try {
            $signedInModel = new SignedInModel($app);
            $id = (int) $request->get('id', 0);
            $user= $signedInModel->getUser();
            // default values:

            if (count($user)) {
                $form = $app['form.factory']->createBuilder(new UpdateProfileForm($app), $user)
                    ->getForm();

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();
                    $user = array(
                        'name' => $data['name'],
                        'surname' => $data['surname'],
                        'email' => $data['email'],
                        'phone_number' => $data['phone_number'],
                    );
                    $signedinModel = new SignedInModel($app);
                    $signedinModel->updateProfile($user, $id);
                    $app['session']->getFlashBag()->add(
                        'message',
                        array(
                            'type' => 'success',
                            'content' => $app['translator']->trans('Changes have been saved')
                        )
                    );
                    return $app->redirect(
                        $app['url_generator']->generate('auth_data'),
                        301
                    );
                }

                $view = parent::getView();
                $view['id'] = $id;
                $view['form'] = $form->createView();

            } else {
                return $app->redirect(
                    $app['url_generator']->generate('index'),
                    301
                );
            }
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
        return $app['twig']->render('auth/update_profile.twig', $view);
    }

     /**
     * Delete action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function deleteAction(Application $app, Request $request)
    {
        try {
            $id = (int) $request->get('id', 0);

            if ($request->getMethod() == $request::METHOD_POST) {
                $signedInModel = new SignedInModel($app);
                $signedInModel->deleteProfile($id);
                $app['session']->getFlashBag()->add(
                    'message',
                    array(
                        'type' => 'success',
                        'content' => $app['translator']->trans('Profile deleted.')
                    )
                );
                return $app->redirect(
                    $app['url_generator']->generate(
                        'index',
                        array('id' => $id)
                    ),
                    301
                );
            }

            $view = parent::getView();
            $view['id'] = $id;
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
        return $app['twig']->render('auth/delete.twig', $view);
    }

    /**
     * Password edit action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function passwordAction(Application $app, Request $request)
    {
        try {
            $signedInModel = new SignedInModel($app);
            $id = (int) $request->get('id', 0);
            $data=array();
            // default values:

            $form = $app['form.factory']->createBuilder(new UpdatePasswordForm($app), $data)
                ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $actualPassword = (string)$signedInModel->getActualPassword();
                $oldPassword = (string)$app['security.encoder.digest']->encodePassword($data['old_password'], '');

                if ($actualPassword == $oldPassword) {
                    if ($data['password_repeated'] == $data['password']) {
                        $data['password'] = $app['security.encoder.digest']->encodePassword($data['password'], '');
                        $password = array (
                            'password' => $data['password']
                        );

                        $signedInModel = new SignedInModel($app);
                        $signedInModel->updatePassword($password, $id);
                        
                        $app['session']->getFlashBag()->add(
                            'message',
                            array(
                                'type' => 'success',
                                'content' => $app['translator']->trans('Password has been changed!')
                            )
                        );

                        return $app->redirect(
                            $app['url_generator']->generate('auth_data'),
                            301
                        );
                    } else {
                        $app['session']->getFlashBag()->add(
                            'message',
                            array(
                                'type' => 'danger',
                                'content' => $app['translator']->trans('Passwords do not match!')
                            )
                        );
                        return $app->redirect(
                            $app['url_generator']->generate(
                                'password_edit',
                                array('id' => $id)
                            ),
                            301
                        );
                    }
                } else {
                    $app['session']->getFlashBag()->add(
                        'message',
                        array(
                            'type' => 'danger',
                            'content' => $app['translator']->trans('Wrong old password!')
                        )
                    );

                    return $app->redirect(
                        $app['url_generator']->generate(
                            'password_edit',
                            array('id' => $id)
                        ),
                        301
                    );
                }
            }
                $view = parent::getView();
                $view['id'] = $id;
                $view['form'] = $form->createView();
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
        return $app['twig']->render('auth/update_password.twig', $view);
        
    }

    /**
     * Index offers action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function indexOffersAction(Application $app, Request $request)
    {
        try {
            $view = parent::getView();
            $signedInModel = new SignedInModel($app);
            $view['posts'] = $signedInModel->getUsersOffers();
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
        return $app['twig']->render('auth/myoffers.twig', $view);
    }

    /**
     * View offer action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function viewOfferAction(Application $app, Request $request)
    {
        try {
            $id = (int)$request->get('id', null);
            $signedInModel = new SignedInModel($app);
            $view = parent::getView();
            $view['post'] = $signedInModel->getOffer($id);
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
        return $app['twig']->render('auth/view.twig', $view);
    }
}
