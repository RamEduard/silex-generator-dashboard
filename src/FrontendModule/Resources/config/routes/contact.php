<?php

/**
 * Contact Routes
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

$app->match('/contact', 'FrontendModule\\Controller\\ContactController::contact')
->method('GET|POST')
->bind('contact');
