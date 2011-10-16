<?php

namespace Majisti\UtilsBundle\Tests\DataFixtures;

use Majisti\UtilsBundle\DataFixtures\AbstractFixture;

class AbstractFixtureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mock;

    /*
     * (non-phpDoc) 
     * @see Inherited documentation.
     */
    public function setUp()
    {
        $this->mock = $this->getMock('Majisti\UtilsBundle\DataFixtures\AbstractFixture', array(
            'dummyLoad', 'craftyLoad',
        ));
    }

    /*
     * (non-phpDoc) 
     * @see Inherited documentation.
     */
    public function tearDown()
    {
        AbstractFixture::resetMode();
    }

    public function testDummyLoad()
    {
        $mock = $this->mock;
        $mock->expects($this->once())
             ->method('dummyLoad');
        $mock->expects($this->never())
             ->method('craftyLoad');

        AbstractFixture::setMode(AbstractFixture::MODE_DUMMY);

        $mock->load(null);
    }

    public function testCraftyLoad()
    {
        $mock = $this->mock;
        $mock->expects($this->once())
             ->method('dummyLoad');
        $mock->expects($this->once())
             ->method('craftyLoad');

        $mock->load(null); //default should be dummy

        AbstractFixture::setMode(AbstractFixture::MODE_CRAFTY);

        $mock->load(null);
    }
}