<?php

/**
 * __TABLENAME__ routes
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

/**
 * List JSON route
 */
$app->match('/admin/__TABLENAME__/list', '__CONTROLLER__::listJson');

/**
 * Download route
 */
$app->match('/admin/__TABLENAME__/download', '__CONTROLLER__::download');

/**
 * List route
 */
$app->get('/admin/__TABLENAME__', '__CONTROLLER__::index')
    ->bind('__TABLENAME___list');

/**
 * Create route
 */
$app->match('/admin/__TABLENAME__/create', '__CONTROLLER__::create')
    ->bind('__TABLENAME___create');

/**
 * Edit route
 */
$app->match('/admin/__TABLENAME__/edit/{id}', '__CONTROLLER__::edit')
    ->bind('__TABLENAME___edit');

/**
 * Delete route
 */
$app->match('/admin/__TABLENAME__/delete/{id}', '__CONTROLLER__::delete')
    ->bind('__TABLENAME___delete');