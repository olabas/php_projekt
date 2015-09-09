<?php

/**
 * Admin controller.
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
use Model\PostsModel;
use Model\FiltersModel;
use Model\UsersModel;
use Model\SignedInModel;

/**
 * Class AdminController.
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
 * @use Model\UsersModel;
 * @use Model\SignedInModel;
 */
class AdminController extends BaseController implements ControllerProviderInterface
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
        $adminController = $app['controllers_factory'];
        $adminController->get('/', array($this, 'indexUsersAction'))->bind('index_users');
        $adminController->get('/users', array($this, 'indexUsersAction'));
        $adminController->get('/users/', array($this, 'indexUsersAction'));
        $adminController->match('/posts', array($this, 'indexPostsAction'))
            ->bind('index_posts');
        $adminController->match('/posts/', array($this, 'indexPostsAction'));
         $adminController->post('/delete/{id}', array($this, 'deletePostAction'));
        $adminController->match('/delete/{id}', array($this, 'deletePostAction'))
            ->bind('delete_post');
        $adminController->match('/delete/{id}/', array($this, 'deletePostAction'));
         $adminController->post('/delete_user/{id}', array($this, 'deleteUserAction'));
        $adminController->match('/delete_user/{id}', array($this, 'deleteUserAction'))
            ->bind('delete_user');
        $adminController->match('/delete_user/{id}/', array($this, 'deleteUserAction'));

        return $adminController;
    }

    /**
     * Index posts action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function indexPostsAction(Application $app, Request $request)
    {
        try {
            $view = parent::getView();
            $postsModel = new PostsModel($app);
            $filtersModel = new FiltersModel($app);
            $view['posts'] = $postsModel->getAll();
            $view['categories'] = $filtersModel->getAllCategories();
            $view['cities'] = $filtersModel->getAllCities();
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
        return $app['twig']->render('admin/index_posts.twig', $view);
    }

 /**
     * Index users action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function indexUsersAction(Application $app, Request $request)
    {
        try {
            $view = parent::getView();
            $usersModel = new UsersModel($app);
            $view['users'] = $usersModel->getAll();
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
        return $app['twig']->render('admin/index_users.twig', $view);
    }

     /**
     * Delete post action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function deletePostAction(Application $app, Request $request)
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
                            'index_posts',
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
        return $app['twig']->render('admin/delete_post.twig', $view);
    }

     /**
     * Delete user action.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @param Symfony\Component\HttpFoundation\Request $request Request object
     * @return string Output
     */
    public function deleteUserAction(Application $app, Request $request)
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
                        'index_users',
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
        return $app['twig']->render('admin/delete_user.twig', $view);
    }
}
