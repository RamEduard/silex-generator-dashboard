<?php

/**
 * Default route
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */
$app->match("/angularjs", "AngularJSModule\\Controller\\DefaultController::index")
    ->bind("angularjs_index");

$app->match("/api/auth/basic","AngularJSModule\\Controller\\DefaultController::basicAuth")
    ->bind('angularjs_auth_basic');
