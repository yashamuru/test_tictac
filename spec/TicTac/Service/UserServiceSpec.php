<?php

namespace spec\TicTac\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserServiceSpec extends CommonServiceSpec
{
    function it_is_initializable()
    {
        $this->shouldHaveType('TicTac\Service\UserService');
    }
    
}
