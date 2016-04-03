<?php

/**
 * Auth routes
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

$app->get('/login', '__CONTROLLER__::login')
    ->bind('login');