<?php

namespace spec\TicTac\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $userId = 15;
        $this->beConstructedWith($userId);
        $this->shouldHaveType('TicTac\Domain\User');
    }
    
    function it_gets_id() 
    {
        $userId = 15;
        $this->beConstructedWith($userId);
        $this->getId()->shouldReturn($userId);        
    }
}
