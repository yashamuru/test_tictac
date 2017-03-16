<?php

namespace spec\TicTac\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GameServiceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TicTac\Service\GameService');
    }
}
