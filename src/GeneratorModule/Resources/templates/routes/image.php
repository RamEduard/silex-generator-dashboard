<?php

/**
 * Image routes
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

$app->match('/admin/image', '__CONTROLLER__::index')
    ->bind('image_list');

$app->match('/admin/imagenJson', '__CONTROLLER__::listJson')
    ->method('GET')
    ->bind('image_list_json');

$app->match('/admin/image/create', '__CONTROLLER__::create')
    ->bind('image_create');

$app->match('/admin/image/edit/{id}', '__CONTROLLER__::edit')
    ->bind('image_edit');

$app->match('/admin/image/delete/{id}', '__CONTROLLER__::delete')
    ->bind('image_delete');
