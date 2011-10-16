<?php

namespace Majisti\UtilsBundle\Test;

use Behat\MinkBundle\Test\MinkTestCase as BaseMinkTestCase;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;

/**
 * Abstract test case for functional testing.
 * 
 * @author Steven Rosato
 */
class MinkTestCase extends BaseMinkTestCase implements PersistentTest
{
    /*
     * (non-phpDoc) 
     * @see Inherited documentation.
     */
    public function loadFixtures(array $classnames)
    {
        $fp = new FixturesProxy($this->createKernel());
        $fp->load($classnames);
    }

    /*
     * (non-phpDoc) 
     * @see Inherited documentation.
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
