<?php

/**
 * API routes
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

$app->match('/restricted', 'APIModule\\Controller\\APIController::restricted')
    ->bind('restricted');
