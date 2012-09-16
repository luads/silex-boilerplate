<?php
// libs, bussiness logic, etc...

// deps Silex\Provider\SessionServiceProvider
use Symfony\Component\HttpFoundation\Session\Session;
// deps Silex\Provider\TwigServiceProvider
use Twig_Environment as Twig;

/**
 * Bootstrap flash messages for twig templates
 *
 * @return void
 * @author Jessé Alves Galdino
 **/
function bootstrap_flash(Session $session, Twig $twig)
{
    $flash = $session->get('flash');
    $session->set('flash', null);

    if (!empty($flash))
    {
        $twig->addGlobal('flash', $flash);
    }
}