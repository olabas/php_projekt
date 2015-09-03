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
        $view['users'] = $signedInModel->getUser();
        return $app['twig']->render('auth/data.twig', $view);
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
