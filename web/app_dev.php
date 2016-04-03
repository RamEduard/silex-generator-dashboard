<?php

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1')) || php_sapi_name() === 'cli-server')
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

# Loader
require_once __DIR__ . '/../app/autoload.php';

if (in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1')) || php_sapi_name() === 'cli-server') {
    $asset_path = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['BASE'] .  '/assets';
    $upload_path = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['BASE'] .  '/uploads';
    $modules_path = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['BASE'] .  '/modules';
} else {
    $asset_path = 'http://' . $_SERVER['SERVER_NAME'] . '/assets';
    $upload_path = 'http://' . $_SERVER['SERVER_NAME'] . '/uploads';
    $modules_path = 'http://' . $_SERVER['SERVER_NAME'] . '/modules';
}

# Application
$app = new App(array(
    'debug'        => true,
    'upload_dir'   => __DIR__ . '/uploads/',
    'asset_path'   => $asset_path,
    'upload_path'  => $upload_path,
    'modules_path' => $modules_path
));

# Application Run
$app->run();