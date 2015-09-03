<?php

/**
 * Posts controller.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Model\PostsModel;
use Model\FiltersModel;
use Model\SignedInModel;
use Model\CommentsModel;
use Symfony\Component\Validator\Constraints as Assert;
use Form\PostsForm;
use Form\CommentsForm;
use Controller\DataController;

/**
 * Class PostsController.
 *
 * @package Controller
 * @extends BaseController
 * @implements ControllerProviderInterface
 */
class PostsController extends BaseController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return PostsController Result
     */
    public function connect(Application $app)
    {
        $postsController = $app['controllers_factory'];
        $postsController->get('/', array($this, 'indexAction'))->bind('index');
        $postsController->get('/index', array($this, 'indexAction'));
        $postsController->get('/index/', array($this, 'indexAction'));
        $postsController->match('/view/{id}', array($this, 'viewAction'))
            ->bind('posts_view');
        $postsController->match('/view/{id}/', array($this, 'viewAction'));
        $postsController->post('/add', array($this, 'addAction'));
        $postsController->match('/add', array($this, 'addAction'))
            ->bind('posts_add');
        $postsController->match('/add/', array($this, 'addAction'));
         $postsController->post('/edit/{id}', array($this, 'editAction'));
        $postsController->match('/edit/{id}', array($this, 'editAction'))
            ->bind('posts_edit');
        $postsController->match('/edit/{id}/', array($this, 'editAction'));

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
        $view['cities'] = $filtersModel->getAllCities();
        return $app['twig']->render('posts/index.twig', $view);
    }

    /**
     * Add action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function addAction(Application $app, Request $request)
    {
        $signedInModel = new SignedInModel($app);
        $signedIn = $signedInModel->getUser();
        // default values:
        $data = array();

        $form = $app['form.factory']->createBuilder(new PostsForm($app), $data)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $data['post_date'] = date('Y-m-d H:i:s');
            $data['user_id'] = $signedIn['id'];
            $postsModel = new PostsModel($app);
            $postsModel->addPost($data);
        }
        $view = parent::getView();
        $view['form'] = $form->createView();
        return $app['twig']->render('posts/add.twig', $view);
    }

    /**
     * View action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function viewAction(Application $app, Request $request)
    {
        $id = (int)$request->get('id', null);
        $postsModel = new PostsModel($app);
        $commentsModel = new CommentsModel($app);
        $view = parent::getView();
        $post =  $postsModel->getPost($id);
        $view['post'] = $post;
    
        $signedInModel = new SignedInModel($app);
        $signedIn = $signedInModel->getUser();
        
        $data = array();

        $form = $app['form.factory']->createBuilder(new CommentsForm($app), $data)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $data['comment_date'] = date('Y-m-d H:i:s');
            $data['post_id'] = $post['id'];
            $data['user_id'] = $signedIn['id'];
            $commentsModel = new CommentsModel($app);
            $commentsModel->addComment($data);
        }

        $view['form'] = $form->createView();
        $view['comments'] = $commentsModel->getAll($id);
        return $app['twig']->render('posts/view.twig', $view);
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

        $postsModel = new PostsModel($app);
        $id = (int) $request->get('id', 0);
        $post = $postsModel->getPost($id);

        // default values:
        if (count($post)) {
            $form = $app['form.factory']->createBuilder(new PostsForm($app), $post)
                ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $post = array(
                    'city_id' => $data['city_id'],
                    'category_id' => $data['category_id'],
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'price' => $data['price']
                );
                $postsModel = new PostsModel($app);
                $postsModel->updatePost($post, $id);
                return $app->redirect(
                    $app['url_generator']->generate('index'),
                    301
                );
            }

            $this->view['id'] = $id;
            $this->view['form'] = $form->createView();

        } else {
            return $app->redirect(
                $app['url_generator']->generate('posts_add'),
                301
            );
        }
        return $app['twig']->render('posts/edit.twig', $this->view);
    }
}
