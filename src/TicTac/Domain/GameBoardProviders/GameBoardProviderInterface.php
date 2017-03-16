<?php

namespace TicTac\Domain\GameBoardProviders;

interface GameBoardProviderInterface
{
    public function getSideSize();
    public function getWinningCombos();
    public function getAllSquares();    
}
