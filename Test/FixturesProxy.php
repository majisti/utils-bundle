<?php

namespace Majisti\UtilsBundle\Test;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;

class FixturesProxy
{
    protected $kernel;

    public function __construct($kernel)
    {
        $this->kernel = $kernel;
    }

    public function load($classnames = array())
    {
        $kernel = $this->kernel;
        $kernel->boot();

        $container = $kernel->getContainer();

        $em = $container->get('doctrine.orm.entity_manager');
        $connection = $em->getConnection();

        /* clear cache */
        $em->clear();
        $conf = $em->getConfiguration();
        $caches = array(
            $conf->getResultCacheImpl(),
            $conf->getQueryCacheImpl(),
            $conf->getMetadataCacheImpl(),
        );

        foreach( $caches as $cache ) {
            if( null !== $cache ) {
                $cache->deleteAll();
            }
        }

        if ($connection->getDriver() instanceOf \Doctrine\DBAL\Driver\PDOSqlite\Driver) {
            $params = $connection->getParams();
            $name = isset($params['path']) ? $params['path'] : $params['dbname'];

            if ($container->getParameter('majisti_utils.test.cache_sqlite_db')) {
                $backup = $container->getParameter('kernel.cache_dir').'/test_'.md5(serialize($classnames)).'.sqlite';
                if (file_exists($backup)) {
                    copy($backup, $name);
                    return;
                }
            }

            // TODO: handle case when using persistent connections. Fail loudly?
            $connection->getSchemaManager()->dropDatabase($name);

            $metadatas = $em->getMetadataFactory()->getAllMetadata();
            if (!empty($metadatas)) {
                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
                $schemaTool->createSchema($metadatas);
            }

            $executor = new ORMExecutor($em);
        } else {
            $purger = new ORMPurger();

            $executor = new ORMExecutor($em, $purger);
            $executor->purge();
        }

        if (empty($classnames)) {
            return;
        }

        $classnames = (array) $classnames;
        $loader = new Loader($container);
        foreach ($classnames as $classname) {
            $fixture = new $classname();

            if( $fixture instanceof \Symfony\Component\DependencyInjection\ContainerAwareInterface ) {
                $fixture->setContainer($container);
            }

            $loader->addFixture($fixture);
        }
        $executor->execute($loader->getFixtures(), true);

        $connection->close();

        if (isset($backup)) {
            copy($name, $backup);
        }
    }
}
