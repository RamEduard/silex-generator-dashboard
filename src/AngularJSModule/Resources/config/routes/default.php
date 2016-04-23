<?php

use Symfony\Component\HttpFoundation\Request;

/**
 * Default routes
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

// Accepts JSON Requests
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

// Angular JS App view
$app->match("/angularjs", "AngularJSModule\\Controller\\DefaultController::index")
    ->bind("angularjs_index");

// HTTP Basic auth
$app->match("/api/auth/basic", "AngularJSModule\\Controller\\DefaultController::basicAuth")
    ->bind('angularjs_auth_basic');

// Form data auth
$app->match("/api/auth/form", "AngularJSModule\\Controller\\DefaultController::formAuth")
    ->bind('angularjs_auth_form');

// Sign Up Example
$app->match("/api/sign-up", "AngularJSModule\\Controller\\DefaultController::signUp")
    ->bind('angularjs_signup');