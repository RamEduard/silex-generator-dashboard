<?php

/**
 * Application autoload
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('', __DIR__ . '/../src');
$loader->addClassMap(array(
    'App' => __DIR__ . '/App.php'
));