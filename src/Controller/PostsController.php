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
        $view = array();
        $postsModel = new PostsModel($app);
        $view['posts'] = $postsModel->getAll();
        return $app['twig']->render('posts/index.twig', $view);
    }
}
