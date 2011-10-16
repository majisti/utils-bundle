<?php

namespace Majisti\UtilsBundle\Tests\DataFixtures;

use Majisti\UtilsBundle\DataFixtures\AbstractManager;
use Majisti\UtilsBundle\DataFixtures\AbstractFixture;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface; 

/**
 * Tests the abstract manager.
 * 
 * @author Steven Rosato
 */
class AbstractManagerTest extends \PHPUnit_Framework_TestCase
{
    /*
     * (non-phpDoc) 
     * @see Inherited documentation.
     */
    public function setUp()
    {
        $this->fixtureA = new FixtureA();
        $this->fixtureB = new FixtureB();
        $this->fixtureC = new FixtureC();
    }

    public function testManagedOrder()
    {
        $this->assertEquals(1, $this->fixtureA->getOrder());
        $this->assertEquals(2, $this->fixtureB->getOrder());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonManagedFixtureThrowsException()
    {
        $this->fixtureC->getOrder();
    }
}

class Manager extends AbstractManager
{
    static protected $fixtures = array(
        'Majisti\UtilsBundle\Tests\DataFixtures\FixtureA',
        'Majisti\UtilsBundle\Tests\DataFixtures\FixtureB',
    );
}

class FixtureA extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return Manager::getOrder($this);
    }
}

class FixtureB extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return Manager::getOrder($this);
    }
}

class FixtureC extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return Manager::getOrder($this);
    }
}
