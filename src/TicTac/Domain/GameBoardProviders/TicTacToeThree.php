<?php

namespace TicTac\Domain\GameBoardProviders;

class TicTacToeThree implements GameBoardProviderInterface
{
    const BOARD_SIDE_SIZE = 3;
    
    public function getSideSize() 
    {
        return self::BOARD_SIDE_SIZE;
    }
    
    public function getWinningCombos() 
    {
        return [
            [0,4,8], [2,4,6],          //Diagonals.
            [0,1,2], [3,4,5], [6,7,8], //Rows
            [0,3,6], [1,4,7], [2,5,8]  //Cols
        ];
    }
    
    public function getAllSquares() 
    {   
        $squares = [];
        for($row = 0; $row <= self::BOARD_SIDE_SIZE -1; $row++) {
            for($col = 0; $col <= self::BOARD_SIDE_SIZE -1; $col++) {
                $squares[] = $row*self::BOARD_SIDE_SIZE + $col;
            }
        }
        return $squares;    
    }
}
