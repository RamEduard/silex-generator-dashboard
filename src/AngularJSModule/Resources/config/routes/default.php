<?php

/**
 * Default route
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */
$app->match("/angularjs", "AngularJSModule\\Controller\\DefaultController::index")
    ->bind("generate_default_index");
