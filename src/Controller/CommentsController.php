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

/**
 * Class CommentsController.
 *
 * @package Controller
 * @extends BaseController
 * @implements ControllerProviderInterface
 * @use Silex\Application;
 * @use Silex\ControllerProviderInterface;
 * @use Symfony\Component\HttpFoundation\Request;
 * @use Symfony\Component\Validator\Constraints as Assert;
 * @use Model\FiltersModel;
 * @use Model\SignedInModel;
 * @use Model\CommentsModel;
 * @use Form\CommentsForm;
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
        try {
            $commentsModel = new CommentsModel($app);
            $id = (int) $request->get('id', 0);
            $comment = $commentsModel->getComment($id);
            $signedInModel = new SignedInModel($app);
            $user = $signedInModel->getUser();
            // default values:
            if (count($comment)) {
                if ($comment['user_id'] == $user['id']) {
                    $form = $app['form.factory']->createBuilder(new CommentsForm($app), $comment)
                        ->getForm();

                    $form->handleRequest($request);

                    if ($form->isValid()) {
                        $comment = $form->getData();
                        $commentsModel = new CommentsModel($app);
                        $commentsModel->updateComment($comment, $id);
                        $app['session']->getFlashBag()->add(
                            'message',
                            array(
                            'type' => 'success',
                            'content' => $app['translator']->trans('Changes have been saved')
                            )
                        );
                        return $app->redirect(
                            $app['url_generator']->generate('posts_view', array('id' => $comment['post_id'])),
                            301
                        );
                    }
                } else {
                    $app['session']->getFlashBag()->add(
                        'message',
                        array(
                            'type' => 'danger',
                            'content' => $app['translator']->trans('It is not your comment! You can not edit it.')
                        )
                    );
                    return $app->redirect(
                        $app['url_generator']->generate(
                            'posts_view',
                            array('id' => $comment['post_id'])
                        ),
                        301
                    );
                }
                $view = parent::getView();
                $view['id'] = $id;
                $view['form'] = $form->createView();
            }
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
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
        try {
            $id = (int) $request->get('id', 0);
            $commentsModel = new CommentsModel($app);
            $comment = $commentsModel->getComment($id);
            $signedInModel = new SignedInModel($app);
            $user = $signedInModel->getUser();

            if ($comment['user_id'] == $user['id'] || $user['role_id'] == 1) {
                if ($request->getMethod() == $request::METHOD_POST) {
                    $commentsModel = new CommentsModel($app);
                    $commentsModel->deleteComment($comment['id']);
                    $app['session']->getFlashBag()->add(
                        'message',
                        array(
                            'type' => 'success',
                            'content' => $app['translator']->trans('Comment has been deleted.')
                        )
                    );
                   
                    return $app->redirect(
                        $app['url_generator']->generate(
                            'posts_view',
                            array('id' => $comment['post_id'])
                        ),
                        301
                    );
                }
                $view = parent::getView();
                $view['id'] = $id;
            } else {
                $app['session']->getFlashBag()->add(
                    'message',
                    array(
                        'type' => 'danger',
                        'content' => $app['translator']->trans('It is not your comment! You can not delete it.')
                    )
                );
                return $app->redirect(
                    $app['url_generator']->generate(
                        'posts_view',
                        array('id' => $comment['post_id'])
                    ),
                    301
                );
            }
        } catch (\PDOException $e) {
            $app->abort(404, $app['translator']->trans('Not found.'));
        }
        return $app['twig']->render('comments/delete.twig', $view);
    }
}
