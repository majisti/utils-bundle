<?php

namespace Majisti\UtilsBundle\DataFixtures;

class FixturesException extends \Exception
{
    static public function invalidMode($mode)
    {
        return new self("Mode {$mode} is not valid, use either MODE_DUMMY or MODE_CRAFTY");
    }
}