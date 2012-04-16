<?php

namespace Majisti\UtilsBundle\Command;

use Doctrine\Bundle\FixturesBundle\Command as BaseCommand;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Majisti\UtilsBundle\DataFixtures\AbstractFixture;
use Majisti\UtilsBundle\DataFixtures\FixturesException;

use InvalidArgumentException;

/**
 * Data fixtures load command that introduces fixtures loading modes.
 * 
 * @author Steven Rosato
 */
class LoadDataFixturesDoctrineCommand extends BaseCommand
{
    /*
     * (non-phpDoc) 
     * @see Inherited documentation.
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('majisti:fixtures:load')
              ->addOption('dummy', 'd', InputOption::VALUE_NONE, "Loads the data fixtures in dummy mode")
              ->addOption('crafty', 'c', InputOption::VALUE_NONE, "Loads the data fixtures in crafty mode")
            ->setHelp(<<<EOT
The <info>majisti:doctrine:fixtures:load</info> is the same as doctrine:fixtures:load except that it introduces fixture modes
    <info>See ./app/console doctrine:fixtures:load -h for the original command</info>

Majisti supports two fixtures loading modes: <info>dummy</info> and <info>crafty</info>.
<info>Dummy mode</info> will load only the barebones of data fixtures into the database, good for <info>testing</info>
<info>Crafty mode</info> will load maximum data into the database, perfect for <info>dev</info>, <info>performance</info> and <info>functional/acceptance</info> testing

If no mode is given, the command will guess a mode based on the console environment.
<info>Test</info> environment will load <info>dummy</info> fixtures.
<info>Dev</info> and <info>prod</info> environments will load <info>crafty</info> fixtures.

Thefore running <info>./app/console majisti:doctrine:fixtures:load</info> will load <info>crafty</info> fixtures in a <info>dev</info> environment

Note that fixtures loading modes are entirely arbitrary and data fixture classes must extend <info>Majisit\UtilsBundle\DataFixtures\AbstractFixture</info>
and implement the respective <info>dummyLoad</info> and <info>craftyLoad</info> functions.
EOT
        );
    }

    /*
     * (non-phpDoc) 
     * @see Inherited documentation.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->loadMode($input, $output);

        parent::execute($input, $output);
    }

    /**
     * Loads either dummy or crafty, based on the input settings.
     * 
     * @param InputInterface $input
     * @param OutputInterface $output 
     */
    protected function loadMode(InputInterface $input, OutputInterface $output)
    {
        $dummy = $input->getOption('dummy');
        $crafty = $input->getOption('crafty');

        $mode = false;
        if( $dummy && $crafty ) {
            throw new InvalidArgumentException("Cannot use both dummy and crafty modes at the same time!");
        } else if( $dummy ) {
            $mode = AbstractFixture::MODE_DUMMY;
        } else if( $crafty ) {
            $mode = AbstractFixture::MODE_CRAFTY;
        }

        if( !$mode ) {
            $env = $input->getOption('env');

            if( 'test' === $env ) {
                $mode = AbstractFixture::MODE_DUMMY;
            } else {
                $mode = AbstractFixture::MODE_CRAFTY;
            }
        }

        AbstractFixture::setMode($mode);

        $mode = $dummy ? 'dummy': 'crafty';
        $output->writeln(sprintf("Loading <info>%s</info> fixtures", $mode));
    }
}
