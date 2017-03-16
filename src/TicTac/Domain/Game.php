<?php

namespace TicTac\Domain;
use TicTac\Domain\GameBoardProviders\TicTacToeThree;

class Game
{    
    private $firstPlayerId;
    private $secondPlayerId;
    private $board;
    
    public function __construct($firstPlayerId, $secondPlayerId, $board = array()) 
    {
        $this->firstPlayerId = $firstPlayerId;
        $this->secondPlayerId = $secondPlayerId;
        $this->board = $board;
        
        $this->boardProvider = new TicTacToeThree();
    }
    
    public function getFirstPlayerId()
    {
        return $this->firstPlayerId;
    }
    
    public function getSecondPlayerId()
    {
        return $this->secondPlayerId;
    }
    
    public function getBoard()
    {
        return $this->board;
    }
    
    public function getWinnerId() 
    {
        $firstId = $this->firstPlayerId;
        $secondId = $this->secondPlayerId;
        
        if (empty($this->board) || 
            empty($this->board[$firstId]) || 
            empty($this->board[$secondId])
        ) {
            return false;
        }
        
        $winningCombos = $this->boardProvider->getWinningCombos();
        
        $userIds = [$firstId, $secondId];
        $winnerId = false;
        
        foreach($userIds as $userId) {
            if (count($this->board[$userId]) < $this->boardProvider->getSideSize()) {
                continue;
            }
            
            foreach($winningCombos as $combo) {
                if  (count(array_unique(array_merge($combo,$this->board[$userId]))) === count($this->board[$userId])) {
                    return $userId;
                }                    
            }
        }
        return false;
    }
    
    public function hasFinished() 
    {       
        $firstId = $this->firstPlayerId;
        $secondId = $this->secondPlayerId;
        
        if ($this->getWinnerId()) {
            return true;
        }
        
        if (empty($this->board) || 
            empty($this->board[$firstId]) || 
            empty($this->board[$secondId])
        ) {
            return false;
        }
        
        return count($this->getEmptySquares()) === 0;      
    }
    
    public function getNextPlayerId() 
    {        
        if ($this->hasFinished()) {
            throw new \TicTac\Exception\GameFinishedException();           
        }
        
        $firstId = $this->firstPlayerId;
        $secondId = $this->secondPlayerId;
        
        if (empty($this->board[$firstId])) {
            return $firstId;
        }
        
        if(empty($this->board[$secondId])) {
            return $secondId;
        }
        
        if (count($this->board[$firstId]) <= count($this->board[$secondId])) {
            return $firstId;
        }
        
        return $secondId;
    }
    
    public function getEmptySquares() 
    {
        $arr1 = array();
        $arr2 = array();
        if (!empty($this->board[$this->firstPlayerId])) {
            $arr1 = $this->board[$this->firstPlayerId];
        }
        
        if (!empty($this->board[$this->secondPlayerId])) {
            $arr2 = $this->board[$this->secondPlayerId];
        }
        
        return array_diff($this->boardProvider->getAllSquares(), $arr1, $arr2);
    }
    
    public function makeAMove($userId, $position) 
    {
        if ($userId !== $this->getNextPlayerId()) {
            throw new \TicTac\Exception\GameNotYourTurnException();
        }
        
        if ( !in_array($position, $this->getEmptySquares())) {
            throw new \TicTac\Exception\GameInvalidPositionException($position);            
        }

        $this->board[$userId][] = $position;
    }
    
}
