<?php

/**
 * Messages controller.
 *
 * @link http://wierzba.wzks.uj.edu.pl/13_bassara
 * @author Aleksandra Bassara <olabassara@gmail.com>
 * @copyright Aleksandra Bassara 2015
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Model\MessagesModel;
use Model\SignedInModel;
use Model\UsersModel;
use Symfony\Component\Validator\Constraints as Assert;
use Form\MessagesForm;

/**
 * Class MessagesController.
 *
 * @package Controller
 * @extends BaseController
 * @implements ControllerProviderInterface
 */
class MessagesController extends BaseController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @access public
     * @param Silex\Application $app Silex application
     * @return MessagesController Result
     */
    public function connect(Application $app)
    {
        $messagesController = $app['controllers_factory'];
        $messagesController->get('/', array($this, 'indexAction'))
            ->bind('messages_index');
        $messagesController->get('/index', array($this, 'indexAction'));
        $messagesController->get('/index/', array($this, 'indexAction'));
        $messagesController->get('/view', array($this, 'viewAction'))
            ->bind('messages_view');
        $messagesController->get('/view/', array($this, 'viewAction'));
        $messagesController->match('/send/{id}', array($this, 'addAction'))
            ->bind('messages_send');
        $messagesController->match('/send/{id}/', array($this, 'addAction'));
        $messagesController->get('/delete/{id}', array($this, 'deleteAction'))
            ->bind('messages_delete');
        $messagesController->get('/delete/{id}/', array($this, 'deleteAction'));

        return $messagesController;
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
        $messagesModel = new MessagesModel($app);
        $view['users'] = $messagesModel->getMessages();
        $view['messages'] = $messagesModel->getMessages();
        return $app['twig']->render('messages/index.twig', $view);
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
        $messagesModel = new MessagesModel($app);
        $view = parent::getView();
        $message =  $messagesModel->getMessage($id);
        $view['message'] = $message;
       
        if ($message['is_read'] == 0) {
            $messagesModel->changeMessageStatus($message);
        }
        return $app['twig']->render('messages/view.twig', $view);
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
        $usersModel = new UsersModel($app);
        $id = (int)$request->get('id', null);
        $signedIn = $signedInModel->getUser();
        $recipient = $usersModel->getUser($id);

        if (count($recipient)) {
            // default values:
            $data = array();

            $form_message = $app['form.factory']->createBuilder(new MessagesForm(), $data)
                ->getForm();

            $form_message->handleRequest($request);

            if ($form_message->isValid()) {
                $messagesModel = new MessagesModel($app);
                $data = $form_message->getData();
                $data['date'] = date('Y-m-d H:i:s');
                $data['is_read'] = 0;
                $data['sender_id'] = (int)$signedIn['id'];
                $data['recipient_id'] = (int)$recipient['id'];
                $messagesModel->sendMessage($data);
                return $app->redirect(
                    $app['url_generator']->generate(
                        'index'
                    ),
                    301
                );
            }

            
            $view = parent::getView();
            $view['form'] = $form_message->createView();
            $view['recipient'] = $recipient;
        }
        return $app['twig']->render('messages/add.twig', $view);
    }
}
