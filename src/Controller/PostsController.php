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
use Model\PostsModel;
use Model\FiltersModel;

class PostsController extends BaseController implements ControllerProviderInterface
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
        $postsController = $app['controllers_factory'];
        $postsController->get('/', array($this, 'indexAction'))->bind('index');
        $postsController->get('/index', array($this, 'indexAction'));
        $postsController->get('/index/', array($this, 'indexAction'));
        return $postsController;
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
        $postsModel = new PostsModel($app);
        $filtersModel = new FiltersModel($app);
        $view['posts'] = $postsModel->getAll();
        $view['categories'] = $filtersModel->getAllCategories();
        $view['states'] = $filtersModel->getAllStates();
        return $app['twig']->render('posts/index.twig', $view);
    }
}
