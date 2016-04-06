<?php

namespace DoctrineORM\Provider;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Created by PhpStorm.
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */
class DoctrineServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $app['orm.default_options'] = array(
            'params'       => array(
                'charset'  => null,
                'driver'   => 'pdo_mysql',
                'dbname'   => null,
                'host'     => 'localhost',
                'user'     => 'root',
                'password' => null,
                'port'     => null,
            ),
            'dev_mode'     => false,
            'etities_type' => 'annotations',
            'path_entities' => array(
                __DIR__ . '/../../Application/Entity'
            ),
            'proxy_dir'    => null
        );

        // ORM
        $app['orm.em'] = $app->share(function($app) {
            $options = $app['orm.default_options'];

            if (empty($options['path_entities']) || !is_array($options['path_entities'])) {
                throw new \Exception('Option path_entities should be an array of path files entities.');
            }

            if ($options['etities_type'] == 'annotations') {
                $config = Setup::createAnnotationMetadataConfiguration($options['path_entities'], $options['dev_mode'], $options['proxy_dir']);
            } elseif ($options['etities_type'] == 'yaml' || $options['etities_type'] == 'yml') {
                $config = Setup::createYAMLMetadataConfiguration($options['path_entities'], $options['dev_mode'], $options['proxy_dir']);
            } elseif ($options['etities_type'] == 'xml') {
                $config = Setup::createXMLMetadataConfiguration($options['path_entities'], $options['dev_mode'], $options['proxy_dir']);
            }

            return EntityManager::create($app['db'], $config);
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app) { }
}