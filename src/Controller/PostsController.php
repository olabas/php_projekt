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
use Symfony\Component\Validator\Constraints as Assert;

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
        $postsController->post('/add', array($this, 'addAction'));
        $postsController->match('/add', array($this, 'addAction'))
            ->bind('posts_add');
        $postsController->match('/add/', array($this, 'addAction'));

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

    public function addAction(Application $app, Request $request)
    {
        // default values:
        $data = array(
            'title' => 'Title',
            'content' => 'Content',
            'price' => 'Price'
        );

        $form = $app['form.factory']->createBuilder('form', $data)
            ->add(
                'title', 'text',
                array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array('min' => 5))
                    )
                )
            )
            ->add(
                'content', 'textarea',
                array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array('min' => 5))
                    )
                )
            )

            ->add(
                'price', 'text',
                array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array('min' => 2))
                    )
                )
            )
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $postsModel = new PostsModel($app);
            $postsModel->addPost($data);
        }
        $view = parent::getView();
        $view['form'] = $form->createView();

        return $app['twig']->render('posts/add.twig', $view);
    }

}
