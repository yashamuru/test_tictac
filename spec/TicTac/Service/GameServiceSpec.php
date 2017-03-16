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
    
    function it_should_throw_user_not_found_on_make_move()
    {
        $pos = 150;
        $gameId = 10;
            
        $userRepo = $this->getUserRepo();
        $userRepo->findById(1)->willReturn(false);
        $this->beConstructedWith($userRepo->reveal(), $this->getGameRepo()->reveal());
        $this->shouldThrow('\TicTac\Exception\EntityNotFoundException')->duringMakeMove($gameId,1, $pos);        
    }
    
    function it_should_throw_game_not_found_on_make_move()
    {
        $faker = \Faker\Factory::create();
        $gameId = $faker->randomNumber(1);
        $userId = $faker->randomNumber(3);
        $pos = 3;
        
        $userRepo = $this->getUserRepo();
        $userRepo->findById($userId)->willReturn(new User($userId));
        
        $gameRepo = $this->getGameRepo();
        $gameRepo->findById($gameId)->willReturn(false);
        
        $this->beConstructedWith($userRepo->reveal(), $gameRepo->reveal());
        $this->shouldThrow('\TicTac\Exception\EntityNotFoundException')->duringMakeMove($gameId, $userId, $pos);        
    }
    
    function it_should_make_move()
    {
        $faker = \Faker\Factory::create();
        $gameId = $faker->randomNumber(1);
        $userId = $faker->randomNumber(3);
        $otherUserId = $faker->randomNumber(3);
        $pos = 7;
        
        $board = [
            $userId => [2,4],
            $otherUserId => [6,8]
        ];
        $newBoard = $board;
        $newBoard[$userId][] = $pos;
        
        $game = new Game($userId, $otherUserId, $board);
        
        $userRepo = $this->getUserRepo();
        $userRepo->findById($userId)->willReturn(new User($userId));
        
        $gameRepo = $this->getGameRepo();
        $gameRepo->findById($gameId)->willReturn($game);
        $gameRepo->save(new Game($userId, $otherUserId, $newBoard))->shouldBeCalled();
        
        $this->beConstructedWith($userRepo->reveal(), $gameRepo->reveal());
        $this->makeMove($gameId, $userId, $pos)->shouldBe(true);        
    }

    function it_should_throw_game_not_found_on_get_status()
    {
        $faker = \Faker\Factory::create();
        $gameId = $faker->randomNumber(1);
        $gameRepo = $this->getGameRepo();
        $gameRepo->findById($gameId)->willReturn(false);
        
        $this->beConstructedWith($this->getUserRepo()->reveal(), $gameRepo->reveal());
        $this->shouldThrow('\TicTac\Exception\EntityNotFoundException')->duringGetStatus($gameId); 
    }
    
    function it_should_get_status()
    {
        $faker = \Faker\Factory::create();
        $hasFinished = $faker->boolean;
        $winnerId = $faker->randomNumber(3);
        $gameId = $faker->randomNumber(1);
        
        $prophet = new \Prophecy\Prophet;
        $gameStub = $prophet->prophesize('TicTac\Domain\Game');
        $gameStub->hasFinished()->willReturn($hasFinished);
        $gameStub->getWinnerId()->willReturn($winnerId);
        
        $gameRepo = $this->getGameRepo();
        $gameRepo->findById($gameId)->willReturn($gameStub->reveal());
        
        $this->beConstructedWith($this->getUserRepo()->reveal(), $gameRepo->reveal());
        $this->getStatus($gameId)->shouldBe([
            'finished'=> $hasFinished,
            'winnerId' => $winnerId
       ]); 
    }
}
