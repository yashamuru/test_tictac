<?php

namespace spec\TicTac\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TicTac\Domain\User;
use TicTac\Domain\Game;

class GameServiceSpec extends CommonServiceSpec
{
    function it_is_initializable()
    {
        $this->beConstructedWith($this->getUserRepo()->reveal(), $this->getGameRepo()->reveal());
        $this->shouldHaveType('TicTac\Service\GameService');
    }
    
    function it_should_throw_user_not_found_on_start_game() 
    {
        $userRepo = $this->getUserRepo();
        $userRepo->findById(1)->willReturn(new User(2));
        $userRepo->findById(2)->willReturn(false);
        
        $this->beConstructedWith($userRepo->reveal(), $this->getGameRepo()->reveal());
        $this->shouldThrow('\TicTac\Exception\EntityNotFoundException')->duringStartGame(1,2);        
    }
    
    function it_should_start_game() 
    {
        $faker = \Faker\Factory::create();
        $firstUserId = $faker->randomNumber(3);
        $secondUserId = $faker->randomNumber(3);
        $gameId = $faker->randomNumber(5);
        
        $game = new Game($firstUserId, $secondUserId, array());
        
        $userRepo = $this->getUserRepo();
        $userRepo->findById($firstUserId)->willReturn(new User($firstUserId));
        $userRepo->findById($secondUserId)->willReturn(new User($secondUserId));
        
        $gameRepo = $this->getGameRepo();
        $gameRepo->save($game)->willReturn($gameId);
        
        $this->beConstructedWith($userRepo->reveal(), $gameRepo->reveal());
        $this->startGame($firstUserId, $secondUserId)->shouldBe($gameId);
    }
    
    
}
