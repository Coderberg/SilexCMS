<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new TranslationServiceProvider());

/* Fill in your database credentials in /config/database.php */

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array (
        'mysql_read' => include (__DIR__.'/../config/database.php')
        ,
    ),
));


/* Security */

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^/admin',
            'http' => true,
            'users' => array(
                // password is admin
                'admin' => array('ROLE_ADMIN', '$2y$13$k4xZBVa9pNcN54oRbHfvt.vJ6WOqWp.Qq15njYxhzW1m/nlX8MMAK'),
            ),
            'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
            'logout' => array('logout_path' => '/admin/logout', 'invalidate_session' => true),
        ),
    )
    )
    );


$app['translator.domains'] = array(
    'messages' => array(
        'en' => array(
            'Title'       => 'Title',
            'Author'      => 'Author',
            'Text'        => 'Text',
            'Picture'     => 'Picture',
            'Submit'      => 'Submit'
        ),
    ),
);



$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});

return $app;
