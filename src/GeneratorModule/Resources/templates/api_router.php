<?php

/**
 * __TABLENAME__ API routes
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */


/**
 * List route
 */
$app->get('/api/__TABLENAME__', '__CONTROLLER__::index')
    ->bind('__TABLENAME___list');

/**
 * Create route
 */
$app->match('/api/__TABLENAME__/create', '__CONTROLLER__::create')
    ->bind('__TABLENAME___create');

/**
 * Edit route
 */
$app->match('/api/__TABLENAME__/edit/{id}', '__CONTROLLER__::edit')
    ->bind('__TABLENAME___edit');

/**
 * Delete route
 */
$app->match('/api/__TABLENAME__/delete/{id}', '__CONTROLLER__::delete')
    ->bind('__TABLENAME___delete');

/**
 * Restricted route
 */
$app->get('/api/__TABLENAME__/restricted', '__CONTROLLER__::restricted')
    ->bind('__TABLENAME___restricted');