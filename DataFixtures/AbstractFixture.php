<?php

namespace Majisti\UtilsBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture as BaseAbstractFixture;
use Doctrine\ORM\EntityManager;

/**
 * Extended abstract fixture class that will add a mode for all fixtures.
 * Fixtures will load data differently according to their two modes: dummy,
 * and crafy. These mode make it perfect for different situation without
 * having to tweak nor comment any fixture code.
 *
 * One must be carefull though with cross dependencies on fixtures. Reference
 * repositories should be used at the same mode level; cross mode dependencies
 * will not work.
 *
 * The mode defaults to MODE_DUMMY.
 * 
 * @author Steven Rosato
 */
abstract class AbstractFixture extends BaseAbstractFixture
{
    const MODE_DUMMY  = 1;
    const MODE_CRAFTY = 2;

    static protected $mode = 2;

    /**
     * Returns the mode for this fixture, based on the class constants.
     * 
     * @return The mode 
     */
    static public function getMode()
    {
        return static::$mode;
    }

    /**
     * Sets the load mode. Use the class constants.
     * 
     * @param int $mode 
     */
    static public function setMode($mode)
    {
        static::$mode = $mode;
    }

    /**
     * Resets the mode back to the default, which is MODE_DUMMY.
     */
    static public function resetMode()
    {
        static::$mode = self::MODE_DUMMY;
    }

    /**
     * Loads the fixture according to its mode context.
     * 
     * It is up to the implementing class to define parts of those loading
     * algorithm. 
     * 
     * Inter-dependency is not required, but recommanded. For example, the dummy
     * mode will setup very minimal fixtures, while the crafty mode will use
     * the dummy load AND add more fixtures.
     * 
     * The default mode is MODE_DUMMY.
     * 
     * @param type $manager 
     */
    public function load($manager)
    {
        switch( $this->getMode() ) {
            case self::MODE_DUMMY:
                $this->dummyLoad($manager);
                break;
            case self::MODE_CRAFTY:
                $this->craftyLoad($manager);
                break;
        }
    }

    /**
     * Loads fixtures in dummy mode. Great for unit tests, fast
     * fixture loading, or simply minimal scaffolding. This is the default mode,
     * unless changed.
     * 
     * @param EntityManager $manager 
     */
    public function dummyLoad($manager)
    {

    }

    /**
     * Loads fixtures in an crafty way (lots of complex data). Great for
     * integration/acceptance testing or performance benchmarks.
     * 
     * @param EntityManager $manager 
     */
    public function craftyLoad($manager)
    {

    }
}
