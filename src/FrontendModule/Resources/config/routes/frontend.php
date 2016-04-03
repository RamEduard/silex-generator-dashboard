<?php

$app->match('/', 'FrontendModule\\Controller\\FrontendController::home')
    ->method('GET')
    ->bind('home');

$app->match('/about', 'FrontendModule\\Controller\\FrontendController::about')
    ->method('GET')
    ->bind('about');