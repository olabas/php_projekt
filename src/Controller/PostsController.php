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
use Form\FiltersForm;

/**
 * Class PostsController.
 *
 * @package Controller
 * @extends BaseController
 * @implements ControllerProviderInterface
 * @use Silex\Application;
 * @use Silex\ControllerProviderInterface;
 * @use Symfony\Component\HttpFoundation\Request;
 * @use Symfony\Component\Validator\Constraints as Assert;
 * @use Model\PostsModel;
 * @use Model\FiltersModel;
 * @use Model\SignedInModel;
 * @use Model\CommentsModel;
 * @use Form\PostsForm;
 * @use Form\CommentsForm;
 * @use Form\FiltersForm;
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
        $postsController->match('/', array($this, 'indexAction'))->bind('index');
        $postsController->match('/index', array($this, 'indexAction'));
        $postsController->match('/index/', array($this, 'indexAction'));
        $postsController->match('/filters', array($this, 'filtersAction'))->bind('filters');
        $postsController->match('/filters/', array($this, 'filtersAction'));
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
         $postsController->post('/delete/{id}', array($this, 'deleteAction'));
        $postsController->match('/delete/{id}', array($this, 'deleteAction'))
            ->bind('posts_delete');
        $postsController->match('/delete/{id}/', array($this, 'deleteAction'));

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
        try {
            $view = parent::getView();
            $postsModel = new PostsModel($app);
            $filtersModel = new FiltersModel($app);
            $data = array();

            $form = $app['form.factory']->createBuilder(new FiltersForm($app), $data)
                ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $app['session']->set('filters', $data);
                return $app->redirect(
                    $app['url_generator']->generate(
                        'filters'
                    ),
                    301
                );
            }

            $view['posts'] = $postsModel->getAll();
            $view['form'] = $form->createView();
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
        return $app['twig']->render('posts/index.twig', $view);
    }

    /**
     * Filters action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function filtersAction(Application $app, Request $request)
    {
        try {
            $view = parent::getView();
            $postsModel = new PostsModel($app);
            $filtersModel = new FiltersModel($app);
            $data = array();
            $filters = $app['session']->get('filters');

            $form = $app['form.factory']->createBuilder(new FiltersForm($app), $data)
                ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $app['session']->set('filters', $data);
                return $app->redirect(
                    $app['url_generator']->generate(
                        'filters'
                    ),
                    301
                );
            }

            $view['posts'] = $filtersModel->filterPosts($filters);
            $view['form'] = $form->createView();
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
            return $app['twig']->render('posts/filters.twig', $view);
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
        try {
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
                $app['session']->getFlashBag()->add(
                    'message',
                    array(
                        'type' => 'success',
                        'content' => $app['translator']->trans('Post has been added')
                    )
                );
                return $app->redirect(
                    $app['url_generator']->generate(
                        'index'
                    ),
                    301
                );
            }
            
            $view = parent::getView();
            $view['form'] = $form->createView();
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
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
        try {
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
            $view['users'] = $signedIn;
            $view['form'] = $form->createView();
            $view['comments'] = $commentsModel->getAll($id);
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
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
        try {
            $postsModel = new PostsModel($app);
            $id = (int) $request->get('id', 0);
            $post = $postsModel->getPost($id);
            $view = parent::getView();

            // default values:
            if (count($post)) {
                $form = $app['form.factory']->createBuilder(new PostsForm($app), $post)
                    ->getForm();

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();
                    $post = array(
                        'id' => $data['id'],
                        'city_id' => $data['city_id'],
                        'category_id' => $data['category_id'],
                        'title' => $data['title'],
                        'content' => $data['content'],
                        'price' => $data['price']
                    );
                    $postsModel = new PostsModel($app);
                    $postsModel->updatePost($post, $id);
                    $app['session']->getFlashBag()->add(
                        'message',
                        array(
                           'type' => 'success', 'content' => $app['translator']->trans('Changes have been saved')
                        )
                    );
                    return $app->redirect(
                        $app['url_generator']->generate(
                            'offers_view',
                            array('id' => $id)
                        ),
                        301
                    );
                }

                $view['id'] = $id;
                $view['form'] = $form->createView();

            } else {
                return $app->redirect(
                    $app['url_generator']->generate('posts_add'),
                    301
                );
            }
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
        return $app['twig']->render('posts/edit.twig', $view);
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
            $view = parent::getView();
            if ($request->getMethod() == $request::METHOD_POST) {
                $signedInModel = new SignedInModel($app);
                $user = $signedInModel->getUser();
                $postsModel = new PostsModel($app);
                $postsModel->deletePost($id);
                $app['session']->getFlashBag()->add(
                    'message',
                    array(
                        'type' => 'success',
                        'content' => $app['translator']->trans('Post has been deleted.')
                    )
                );
                return $app->redirect(
                    $app['url_generator']->generate(
                        'auth_myoffers',
                        array('id' => $user['id'])
                    ),
                    301
                );
            }

            $view['id'] = $id;
        } catch (\PDOException $e) {
            $app->abort(
                404,
                $app['translator']->trans('Not found.')
            );
        }
        return $app['twig']->render('posts/delete.twig', $view);
    }
}
