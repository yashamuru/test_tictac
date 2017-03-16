<?php

namespace TicTac\Domain;

class Game
{
    const BOARD_SIZE = 3;
    private $firstPlayerId;
    private $secondPlayerId;
    private $board;
    
    public function __construct($firstPlayerId, $secondPlayerId, $board = array()) {
        $this->firstPlayerId = $firstPlayerId;
        $this->secondPlayerId = $secondPlayerId;
        $this->board = $board;        
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
    
    public function isUserTurn($user_id) {
        return $user_id === $this->getNextPlayerId();
    }
    
    public function getWinnerId() {
        $firstId = $this->firstPlayerId;
        $secondId = $this->secondPlayerId;
        
        if (empty($this->board) || 
            empty($this->board[$firstId]) || 
            empty($this->board[$secondId])
        ) {
            return false;
        }
        
        $winningCombos = [
            [0,4,8], [2,4,6], //diagonals. ToDo - dependent on board size. Tests too !!!
            [0,1,2], [3,4,5], [6,7,8], //Rows
            [0,3,6], [1,4,7], [2,5,8]  //Cols
        ];
        
        $userIds = [$firstId, $secondId];
        $winnerId = false;
        
        foreach($userIds as $userId) {
            if (count($this->board[$userId]) < self::BOARD_SIZE) {
                continue;
            }
            
            foreach($winningCombos as $combo) {
                /*
                print_r($this->board[$userId]);
                print_r($combo);
                print_r(array_unique(array_merge($combo,$this->board[$userId])));
                echo '~~~~'.$userId.'~~~~';
                */
                if  (count(array_unique(array_merge($combo,$this->board[$userId]))) === count($this->board[$userId])) {
                    return $userId;
                }                    
            }
        }
        return false;
    }
    
    public function hasFinished() {
        
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
        
        $movesLeft = count($this->getEmptySquares());
        
        return $movesLeft === 0;
        
        //$totalMoves = count($this->board[$firstId]) + count($this->board[$secondId]);
        //return $totalMoves === pow(self::BOARD_SIZE, 2)-1;
    }
    
    public function getNextPlayerId() {
        
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
    
    public function getEmptySquares() {
        $arr1 = array();
        $arr2 = array();
        if (!empty($this->board[$this->firstPlayerId])) {
            $arr1 = $this->board[$this->firstPlayerId];
        }
        
        if (!empty($this->board[$this->secondPlayerId])) {
            $arr2 = $this->board[$this->secondPlayerId];
        }
        
        return array_diff($this->getAllBoardSquares(), $arr1, $arr2);
    }
    
    public function getAllBoardSquares() {
        $squares = [];
        for($row = 0; $row <= self::BOARD_SIZE -1; $row++) {
            for($col = 0; $col <= self::BOARD_SIZE -1; $col++) {
                $squares[] = $row*self::BOARD_SIZE + $col;
            }
        }
        return $squares;
    }
    
    public function makeAMove($userId, $position) {
        if ($userId !== $this->getNextPlayerId()) {
            throw new \TicTac\Exception\GameNotYourTurnException();
        }
        
        if ( !in_array($position, $this->getEmptySquares())) {
            throw new \TicTac\Exception\GameInvalidPositionException($position);            
        }

        $this->board[$userId][] = $position;        
    }

}
