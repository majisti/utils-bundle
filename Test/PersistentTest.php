<?php

namespace Majisti\UtilsBundle\Test;

use Majisti\UtilsBundle\DataFixtures\FixtureSet;
use Doctrine\ORM\EntityManager;

interface PersistentTest
{
    /**
     * @return FixtureSet
     */
    public function loadFixtures(array $classnames);

    /**
     * @return EntityManager
     */
    public function getEntityManager();
}
