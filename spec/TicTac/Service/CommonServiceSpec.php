<?php

namespace spec\TicTac\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommonServiceSpec extends ObjectBehavior
{
    protected function getUserRepo() 
    {
        return $this->getProphecyForInterface('TicTac\Domain\UserRepositoryInterface');
    }
    
    protected function getGameRepo() 
    {
        return $this->getProphecyForInterface('TicTac\Domain\GameRepositoryInterface');
    }

    protected function getProphecyForInterface($interfaceName)
    {
        $prophet = new \Prophecy\Prophet;
        
        $prophecy = $prophet->prophesize();
        $prophecy->willExtend('stdClass');
        $prophecy->willImplement($interfaceName);
        return $prophecy;
    }
}
