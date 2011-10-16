<?php

namespace Majisti\UtilsBundle\DataFixtures;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Abstract manager that will provide easy ordering for ordered fixtures.
 * 
 * This class must be extended and the fixtures static variable populated with
 * data fixtures classes (that needs ordering). Ex: CarModel, CarBrand and so on.
 * 
 * Data fixture instances can then call this manager to get their order, without
 * having to explicitely add it in their getOrder() function, which centralizes
 * the changes when the orders change over time.
 * 
 * @author Steven Rosato
 */
abstract class AbstractManager
{
    /**
     * @var array 
     */
    static protected $fixtures = array();

    /**
     * Returns the order for an ordered fixture interface, starting at 1.
     * 
     * @param OrderedFixtureInterface $fixture
     * 
     * @throws \InvalidArgumentException If the fixture is not managed
     * 
     * @return int 
     */
    static public function getOrder(OrderedFixtureInterface $fixture)
    {
        $class = get_class($fixture);
        if( false === ($key = array_search($class, static::$fixtures)) ) {
            throw new \InvalidArgumentException(
                "Fixture with class {$class} is not managed.");
        }

        return $key + 1;
    }
}
