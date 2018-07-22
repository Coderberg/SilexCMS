<?php

namespace App\Controllers\Auth;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @author Coderberg
 */
class LoginController
{
    public function login(Request $request, Application $app) 
    {
        return $app['twig']->render('auth/login.html.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }
}
