<?php

namespace Majisti\UtilsBundle\Test;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * @author Steven Rosato
 */
class WebTestCase extends BaseWebTestCase implements PersistentTest
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
