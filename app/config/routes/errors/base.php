<?php

use \PDOException,
    Doctrine\DBAL\DBALException,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException,
    Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->error(function (PDOException $PDOException, $code) use($app) {
    // Guardar la traza en base de datos
    if ($app['debug']) {
        return;
    } else {
        // Aqui ira la vista error/db.html.twig
        return new Response(/*$PDOException->getMessage()*/);
    }
});

$app->error(function (DBALException $DBALException, $code) use($app) {
    // Guardar la traza en base de datos
    if ($app['debug']) {
        return;
    } else {
        // Aqui ira la vista error/db.html.twig
        return new Response($DBALException->getMessage());
    }
});

$app->error(function (\LogicException $logicException) use($app) {
    if ($app['debug']) {
        return;
    } else {
        // Aqui ira la vista error/500.html.twig
        return new Response($logicException->getMessage());
    }
});

$app->error(function (AccessDeniedHttpException $accessDeniedHttpException) use($app) {
    if ($app['debug']) {
        return;
    } else {
        $menuModelo = new Menu($app['db']);
        $menuItems = $menuModelo->getItems();
        
        // Respuesta en frontend
        $uriActual = $_SERVER['REQUEST_URI'];
        
        return $app['twig']->render('errors/403.html.twig', array(
            'uri'        => $uriActual,
            'menu_items' => $menuItems
        ));
    }
});

$app->error(function (NotFoundHttpException $notFoundHttpException) use($app) {
    if ($app['debug']) {
        return;
    } else {
        
        $uriActual = $_SERVER['REQUEST_URI'];
        
        return $app['twig']->render('errors/404.html.twig', array(
            'uri' => $uriActual
        ));
    }
});

$app->error(function (MethodNotAllowedHttpException $methodNotAllowedHttpException, $code) use($app) {
    if ($app['debug']) {
        return;
    } else {
        // Metodo no permitido 405.html.twig
        return new Response($methodNotAllowedHttpException->getMessage());
    }
});

$app->error(function (Twig_Error_Loader $twigError) use($app) {
    if ($app['debug']) {
        return;
    } else {
        // Ocurrio un error en el servido 500.html.twig
        return new Response($twigError->getMessage());
    }
});

$app->error(function (Twig_Error_Runtime $twigErrorRuntime) use($app) {
    if ($app['debug']) {
        return;
    } else {
        // Ocurrio un error en el servido 500.html.twig
        return new Response($twigErrorRuntime->getMessage());
    }
});

$app->error(function(\Swift_TransportException $swift_TransportException) use($app) {
    if ($app['debug']) {
        return;
    } else {
        // Ocurrio un error en el servido 500.html.twig
        return new Response('Ocurrió un error: '. $swift_TransportException->getMessage());
    }
});