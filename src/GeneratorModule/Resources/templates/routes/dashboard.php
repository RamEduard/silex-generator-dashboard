<?php

/**
 * Dashboard routes
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

$app->match('/admin', '__CONTROLLER__::dashboard')
    ->bind('dashboard');
