<?php

/**
 * Data controller.
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

/**
 * Class DataController.
 *
 * @package Controller
 * @extends BaseController
 * @implements ControllerProviderInterface
 */
class DataController extends BaseController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return DataController Result
     */
    public function connect(Application $app)
    {
        $dataController = $app['controllers_factory'];
        $dataController->get('/', array($this, 'indexAction'))->bind('auth_data');
        $dataController->get('/data', array($this, 'indexAction'));
        $dataController->get('/data/', array($this, 'indexAction'));
        $dataController->get('/offers', array($this, 'indexOffersAction'))->bind('auth_myoffers');
        $dataController->get('/offers/', array($this, 'indexOffersAction'));
        $dataController->match('/view/{id}', array($this, 'viewOfferAction'))
            ->bind('offers_view');
        $dataController->match('/view/{id}/', array($this, 'viewOfferAction'));
         $dataController->post('/edit/{id}', array($this, 'editAction'));
        $dataController->match('/edit/{id}', array($this, 'editAction'))
            ->bind('users_edit');
        $dataController->match('/edit/{id}/', array($this, 'editAction'));

        return $dataController;
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
        $view = parent::getView();
        $signedInModel = new SignedInModel($app);
        $view['user'] = $signedInModel->getUser();
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

        $signedInModel = new SignedInModel($app);
        $id = (int) $request->get('id', 0);
        $user= $signedInModel->getUser($id);
        // default values:
        echo 'bleeeeee';
        if (count($user)) {
            $form = $app['form.factory']->createBuilder(new UpdateProfileForm($app), $user)
                ->getForm();

            $form->handleRequest($request);
echo 'bleeeeee2';
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
               echo 'bleeeeee3';
                return $app->redirect(
                    $app['url_generator']->generate('index'),
                    301
                );
            }
echo 'bleeeee4';
            $this->view['id'] = $id;
            $this->view['form'] = $form->createView();

        } else {
            echo 'bleeeee5';
            return $app->redirect(
                $app['url_generator']->generate('index'),
                301
            );
        }
        return $app['twig']->render('auth/update_profile.twig', $this->view);
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
        $view = parent::getView();
        $signedInModel = new SignedInModel($app);
        $view['users'] = $signedInModel->getUsersOffers();
        $view['posts'] = $signedInModel->getUsersOffers();
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
        $id = (int)$request->get('id', null);
        $signedInModel = new SignedInModel($app);
        $view = parent::getView();
        $view['post'] = $signedInModel->getOffer($id);
        return $app['twig']->render('auth/view.twig', $view);
    }
}
