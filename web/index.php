<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', E_ALL);

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => dirname(dirname(__FILE__)) . '/src/views',
));

$app->register(
    new Silex\Provider\SecurityServiceProvider(),
    array(
        'security.firewalls' => array(
            'admin' => array(
                'pattern' => '^.*$',
                'form' => array(
                    'login_path' => '/auth/login',
                    'check_path' => 'auth_login_check',
                    'default_target_path'=> '/',
                    'username_parameter' => 'loginForm[login]',
                    'password_parameter' => 'loginForm[password]',
                ),
                'anonymous' => true,
                'logout' => array(
                    'logout_path' => 'auth_logout',
                    'target_url' => '/'
                ),
                'users' => $app->share(
                    function() use ($app)
                    {
                        return new Provider\UserProvider($app);
                    }
                ),
            ),
        ),
        'security.access_rules' => array(
            array('^/$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/auth.+$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/register.*$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/index.*$', 'ROLE_USER'),
            array('^/view.*$', 'ROLE_USER'),
            array('^/add.*$', 'ROLE_USER'),
            array('^/edit.*$', 'ROLE_USER'),
            array('^/delete.*$', 'ROLE_USER'),
            array('^/filters.*$', 'ROLE_USER'),
            array('^/comments.*$', 'ROLE_USER'),
            array('^/messages.*$', 'ROLE_USER'),
            array('^/users.*$', 'ROLE_USER'),
            array('^/.+$', 'ROLE_ADMIN')
        ),
        'security.role_hierarchy' => array(
            'ROLE_ADMIN' => array('ROLE_USER'),
        ),
    )
);


$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(
    new Silex\Provider\TranslationServiceProvider(), array(
        'locale' => 'pl',
        'locale_fallbacks' => array('pl'),
    )
);

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());
    $translator->addResource('yaml', dirname(dirname(__FILE__)) . '/config/locales/pl.yml', 'pl');
    return $translator;
}));

$app->register(
    new Silex\Provider\DoctrineServiceProvider(), 
    array(
        'db.options' => array(
            'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => '13_bassara',
            'user'      => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            ),
    )
);

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

$app->error(
    function (
        \Exception $e, $code
    ) use ($app) {

        if ($e instanceof Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            $code = (string)$e->getStatusCode();
        }

        if ($app['debug']) {
            return;
        }

        // 404.html, or 40x.html, or 4xx.html, or error.html
        $templates = array(
            'errors/'.$code.'.twig',
            'errors/'.substr($code, 0, 2).'x.twig',
            'errors/'.substr($code, 0, 1).'xx.twig',
            'errors/default.twig',
        );

        return new Response(
            $app['twig']->resolveTemplate($templates)->render(
                array('code' => $code)
            ),
            $code
        );

    }
);

$app->mount('/', new Controller\PostsController());
$app->mount('/comments', new Controller\CommentsController());
$app->mount('/messages', new Controller\MessagesController());
$app->mount('/users', new Controller\UsersController());
$app->mount('/register', new Controller\RegisterController());
$app->mount('/auth', new Controller\AuthController());
$app->mount('/admin', new Controller\AdminController());
$app->run();
