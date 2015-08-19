<?php
/**
 * Hello controller.
 *
 * @link http://epi.uj.edu.pl
 * @author epi(at)uj(dot)edu(dot)pl
 * @copyright EPI 2015
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Model\SignedInModel;

class DataController extends BaseController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return HelloController Result
     */
    public function connect(Application $app)
    {
        $dataController = $app['controllers_factory'];
        $dataController->get('/', array($this, 'indexAction'))->bind('auth_data');
        $dataController->get('/data', array($this, 'indexAction'));
        $dataController->get('/data/', array($this, 'indexAction'));
        $dataController->get('/offers', array($this, 'indexOffersAction'))->bind('auth_myoffers');
        $dataController->get('/offers/', array($this, 'indexOffersAction'));

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

    public function indexOffersAction(Application $app, Request $request)
    {
        $view = parent::getView();
        $signedInModel = new SignedInModel($app);
        $view['users'] = $signedInModel->getUsersOffers();
        $view['posts'] = $signedInModel->getUsersOffers();
        return $app['twig']->render('auth/myoffers.twig', $view);
    }

}