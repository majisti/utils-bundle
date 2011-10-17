<?php

namespace Majisti\UtilsBundle\Test;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArrayInput as ConsoleArrayInput;

use Symfony\Component\Finder\Finder;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

/**
 * @desc Interface for database helpers. Provides fluent interface.
 *
 * @author Steven Rosato
 */
class OrmTestHelper
{
    protected static $kernelClass;
    protected static $kernel;

    /**
     * @return Kernel 
     */
    public function getKernel()
    {
        if( null === static::$kernel ) {
            static::$kernel = static::createKernel();
        }

        return static::$kernel;
    }

    /**
     * @var ConsoleApplication 
     */
    protected $consoleApp;

    public function getEntityManager()
    {
        return $this->getKernel()->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function purgeDatabase()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    /**
     *
     * @return TestHelper 
     */
    public function loadFixtures(array $classnames)
    {
        $fp = new FixturesProxy($this->getKernel());
        $fp->load($classnames);
    }


    /**
     * Creates a Kernel.
     *
     * @param   string      $environment    environment name (test, prod, debug)
     * @param   Boolean     $debug          debug mode
     *
     * @return  HttpKernelInterface A HttpKernelInterface instance
     */
    protected static function createKernel($environment = 'test', $debug = true)
    {
        if (null === self::$kernelClass) {
            self::$kernelClass = static::getKernelClass();
        }

        $kernel = new self::$kernelClass($environment, $debug);
        $kernel->boot();

        return $kernel;
    }

    /**
     * Attempts to guess the kernel location.
     *
     * When the Kernel is located, the file is required.
     *
     * @return string The Kernel class name
     */
    protected static function getKernelClass()
    {
        $dir = isset($_SERVER['KERNEL_DIR']) ? $_SERVER['KERNEL_DIR'] : static::getPhpUnitXmlDir();

        $finder = new Finder();
        $finder->name('*Kernel.php')->depth(0)->in($dir);
        if (!count($finder)) {
            throw new \RuntimeException('You must override the WebTestCase::createKernel() method.');
        }

        $file = current(iterator_to_array($finder));
        $class = $file->getBasename('.php');

        require_once $file;

        return $class;
    }

    /**
     * Finds the directory where the phpunit.xml(.dist) is stored.
     *
     * If you run tests with the PHPUnit CLI tool, everything will work as expected.
     * If not, override this method in your test classes.
     *
     * @return string The directory where phpunit.xml(.dist) is stored
     */
    private static function getPhpUnitXmlDir()
    {
        if (!isset($_SERVER['argv']) || false === strpos($_SERVER['argv'][0], 'phpunit')) {
            throw new \RuntimeException('You must override the WebTestCase::createKernel() method.');
        }

        $dir = static::getPhpUnitCliConfigArgument();
        if ($dir === null &&
            (file_exists(getcwd().DIRECTORY_SEPARATOR.'phpunit.xml') ||
            file_exists(getcwd().DIRECTORY_SEPARATOR.'phpunit.xml.dist'))) {
            $dir = getcwd();
        }

        // Can't continue
        if ($dir === null) {
            throw new \RuntimeException('Unable to guess the Kernel directory.');
        }

        if (!is_dir($dir)) {
            $dir = dirname($dir);
        }

        return $dir;
    }

    /**
     * Finds the value of configuration flag from cli
     *
     * PHPUnit will use the last configuration argument on the command line, so this only returns
     * the last configuration argument
     *
     * @return string The value of the phpunit cli configuration option
     */
    private static function getPhpUnitCliConfigArgument()
    {
        $dir = null;
        $reversedArgs = array_reverse($_SERVER['argv']);
        foreach ($reversedArgs as $argIndex=>$testArg) {
            if ($testArg === '-c' || $testArg === '--configuration') {
                $dir = realpath($reversedArgs[$argIndex - 1]);
                break;
            } elseif (strpos($testArg, '--configuration=') === 0) {
                $argPath = substr($testArg, strlen('--configuration='));
                $dir = realpath($argPath);
                break;
            }
        }

        return $dir;
    }
}
