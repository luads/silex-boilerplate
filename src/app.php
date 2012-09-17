<?php
//bootstrap app
require_once __DIR__.'/bootstrap.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
    'twig.options' => array('cache' => __DIR__.'/../cache'),
    ));
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app['debug'] = true;

// bootstraping flash messages
bootstrap_flash($app['session'], $app['twig']);

$app->get('/', function() use ($app){
	$app['session']->set('flash', array('type' => 'notice', 'message' => 'Hey there.'));
    
    return $app['twig']->render('index.html.twig');
})
->bind('home');

$app->get('/hello', function() use ($app){
	$app['session']->set('flash', array('type' => 'notice', 'message' => 'Hey there.'));
    
	return $app->redirect('/');
})
->bind('hello');


return $app;