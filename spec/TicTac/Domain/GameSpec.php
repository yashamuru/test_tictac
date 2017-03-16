<?php

namespace spec\TicTac\Domain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GameSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $faker = \Faker\Factory::create();
        $this->beConstructedWith($faker->randomNumber(3), $faker->randomNumber(3), []);
        $this->shouldHaveType('TicTac\Domain\Game');
    }
    
    function it_has_working_getters() 
    {
        $faker = \Faker\Factory::create();
        
        $firstUserId = $faker->randomNumber(3);
        $secondUserId = $faker->randomNumber(3);
        $board = array(
            $firstUserId => [0,3,4],
            $secondUserId => [2,6]
        );
        $this->beConstructedWith($firstUserId, $secondUserId, $board);
        
        $this->getFirstPlayerId()->shouldBe($firstUserId);
        $this->getSecondPlayerId()->shouldBe($secondUserId);
        $this->getBoard()->shouldHaveKeyWithValue($firstUserId, [0,3,4]);
        $this->getBoard()->shouldHaveKeyWithValue($secondUserId, [2,6]);
    }
    
    function it_should_give_first_player_first_move() 
    {
        $faker = \Faker\Factory::create();
        $firstUserId = $faker->randomNumber(3);
        $secondUserId = $faker->randomNumber(3);
        $board = array();
        $this->beConstructedWith($firstUserId, $secondUserId, $board);
        $this->getNextPlayerId()->shouldBe($firstUserId);
    }
    
    function it_should_give_second_player_a_move() 
    {
        $faker = \Faker\Factory::create();
        $firstUserId = $faker->randomNumber(3);
        $secondUserId = $faker->randomNumber(3);
        $board = array(
            $firstUserId => [0,3,4],
            $secondUserId => [2,6]
        );
        $this->beConstructedWith($firstUserId, $secondUserId, $board);
        $this->getNextPlayerId()->shouldBe($secondUserId);
    }
    
    function it_should_give_first_player_move_on_even_moves()
    {
        $faker = \Faker\Factory::create();
        $firstUserId = $faker->randomNumber(3);
        $secondUserId = $faker->randomNumber(3);
        $board = array($firstUserId => [5,3,1], $secondUserId => [4,0,2]);
        
        $this->beConstructedWith($firstUserId, $secondUserId, $board);
        $this->getNextPlayerId()->shouldBe($firstUserId);
    }
    
    function it_should_throw_exception_when_asking_for_next_move_on_finished_game() 
    {
        //@ToDo - fix the random element on Conseq calls.
        $firstId = 1;
        $secondId = 2;
        $finishedBoards = [
            [1 => [4,0,2], 2 => [6,7,8]], //Second player wins 
            [1 => [4,0,2,6] , 2 => [1,3,5,7]], //First player wins - 4,2,6 
            [1 => [4,8,3,7,2] , 2 => [6,0,5,1]]            
        ];
        
        $rand = rand(0,count($finishedBoards)-1);
        $this->beConstructedWith($firstId, $secondId, $finishedBoards[$rand]);
        $this->shouldThrow('\TicTac\Exception\GameFinishedException')->duringGetNextPlayerId();
    }
    
    function it_should_give_winner() 
    {
        $firstId = 1;
        $secondId = 2;
        //@ToDo - onConsequtive calls...
        $finishedBoards = [
            ['board'=> [1 => [4,0,2], 2 => [7,8,6]], 'winner' => 2], //Second player wins, 6,7,8
            ['board' => [1 => [4,0,2,6] , 2 => [1,3,5,7]], 'winner' => 1] //First player wins - 4,2,6                        
        ];
        
        $rand = rand(0, count($finishedBoards)-1);
        $this->beConstructedWith($firstId, $secondId, $finishedBoards[$rand]['board']);
        $this->getWinnerId()->shouldBe($finishedBoards[$rand]['winner']);        
    }
    
    function it_should_give_empty_squares() 
    {
        $firstId = 1;
        $secondId = 2;
        $board = array(
            $firstId => [0,3,4],
            $secondId => [2,6]
        );
        
        $this->beConstructedWith($firstId, $secondId, $board);
        
        $this->getEmptySquares()->shouldHaveCount(4);        
        $this->getEmptySquares()->shouldContain(1);        
        $this->getEmptySquares()->shouldContain(5);        
        $this->getEmptySquares()->shouldContain(7);        
        $this->getEmptySquares()->shouldContain(8);        
    }
    
    function it_should_throw_not_your_turn() 
    {
        $firstId = 1;
        $secondId = 2;
        $board = array(
            $firstId => [0,3,4],
            $secondId => [2,6]
        );
        $this->beConstructedWith($firstId, $secondId, $board);
        $this->shouldThrow('\TicTac\Exception\GameNotYourTurnException')->duringMakeAMove($firstId, 5);
    }
    
    function it_should_throw_invalid_position() 
    {
        $firstId = 1;
        $secondId = 2;
        $board = array(
            $firstId => [0,3,4],
            $secondId => [2,6]
        );
        $this->beConstructedWith($firstId, $secondId, $board);
        $this->shouldThrow('\TicTac\Exception\GameInvalidPositionException')->duringMakeAMove($secondId, 10);
    }
    
    function it_should_make_a_move() 
    {
        $firstId = 1;
        $secondId = 2;
        $board = array(
            $firstId => [0,3,4],
            $secondId => [2,5]
        );
        $this->beConstructedWith($firstId, $secondId, $board);
        $this->makeAMove($secondId, 8);
        
        $this->getWinnerId()->shouldBe($secondId);        
    }
    
}
