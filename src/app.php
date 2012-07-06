<?php

require_once __DIR__.'/bootstrap.php';

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