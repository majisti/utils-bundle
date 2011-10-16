<?php

namespace Majisti\UtilsBundle\Test;

use Doctrine\ORM\EntityManager;

abstract class OrmTestCase extends \PHPUnit_Framework_TestCase implements PersistentTest
{
    /**
     * @var TestHelper 
     */
    static protected $helper;

    /**
     * @return OrmTestHelper
     */
    static public function getHelper()
    {
        if ( null === static::$helper ) {
            static::$helper = new OrmTestHelper();
        }

        return static::$helper;
    }

    public function loadFixtures(array $classnames)
    {
        $this->getHelper()->loadFixtures($classnames);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * Returns kernel instance.
     *
     * @return  HttpKernelInterface A HttpKernelInterface instance
     */
    public static function getKernel()
    {
        return static::getHelper()->getKernel();
    }

    /**
     * Returns container instance.
     *
     * @return  ContainerInterface
     */
    public static function getContainer()
    {
        return static::getKernel()->getContainer();
    }
}
