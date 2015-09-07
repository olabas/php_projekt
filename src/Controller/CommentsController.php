<?php

/**
 * Comments controller.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Model\FiltersModel;
use Model\SignedInModel;
use Model\CommentsModel;
use Symfony\Component\Validator\Constraints as Assert;
use Form\CommentsForm;
use Controller\DataController;
use Controller\PostsController;

/**
 * Class CommentsController.
 *
 * @package Controller
 * @extends BaseController
 * @implements ControllerProviderInterface
 */
class CommentsController extends BaseController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return CommentsController Result
     */
    public function connect(Application $app)
    {
        $commentsController = $app['controllers_factory'];
        $commentsController->post('/edit/{id}', array($this, 'editAction'));
        $commentsController->match('/edit/{id}', array($this, 'editAction'))
            ->bind('comments_edit');
        $commentsController->match('/edit/{id}/', array($this, 'editAction'));
        $commentsController->post('/delete/{id}', array($this, 'deleteAction'));
        $commentsController->match('/delete/{id}', array($this, 'deleteAction'))
            ->bind('comments_delete');
        $commentsController->match('/delete/{id}/', array($this, 'deleteAction'));

        return $commentsController;
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

        $commentsModel = new CommentsModel($app);
        $id = (int) $request->get('id', 0);
        $comment = $commentsModel->getComment($id);
        $view = parent::getView();

        // default values:
        if (count($comment)) {
            $form = $app['form.factory']->createBuilder(new CommentsForm($app), $comment)
                ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {
                $comment = $form->getData();
                $commentsModel = new CommentsModel($app);
                $commentsModel->updateComment($comment, $id);
                return $app->redirect(
                    $app['url_generator']->generate('posts_view'),
                    301
                );
            }

            $view['id'] = $id;
            $view['form'] = $form->createView();

        } else {
            return $app->redirect(
                $app['url_generator']->generate('index'),
                301
            );
        }
        return $app['twig']->render('comments/edit.twig', $view);
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
        $id = (int) $request->get('id', 0);
        $commentsModel = new CommentsModel($app);
        $comment = $commentsModel->getComment($id);

        if($request->getMethod() == $request::METHOD_POST)
        {
            $commentsModel = new CommentsModel($app);
            $commentsModel->deleteComment($comment['id']);
            $app['session']->getFlashBag()->add(
                'message', array(
                    'type' => 'success', 'content' => $app['translator']->trans('Comment deleted.')
                )
            );
           
            return $app->redirect(
                $app['url_generator']->generate('index'), 301
            );
        }
        $view = parent::getView();
        $view['id'] = $id;
        return $app['twig']->render('comments/delete.twig', $view);
    }

}
