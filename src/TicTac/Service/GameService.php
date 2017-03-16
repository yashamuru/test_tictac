<?php

namespace TicTac\Service;

use TicTac\Domain\User;
use TicTac\Domain\UserRepositoryInterface;
use TicTac\Domain\Game;
use TicTac\Domain\GameRepositoryInterface;
use TicTac\Exception\EntityNotFoundException;

class GameService
{
    public function __construct(
        UserRepositoryInterface $userRepository, 
        GameRepositoryInterface $gameRepository
    ) {
        $this->userRepository = $userRepository;
        $this->gameRepository = $gameRepository;
    }
    
    public function startGame($firstId, $secondId) {
        $firstPlayer = $this->userRepository->findById($firstId);
        $secondPlayer = $this->userRepository->findById($secondId);
        
        if( ! $firstPlayer ) {
            throw new EntityNotFoundException("User", $firstId);
        }
        
        if ( ! $secondPlayer) {
            throw new EntityNotFoundException("User", $secondId);
        }
        
        $game = new Game($firstPlayer->getId(), $secondPlayer->getId());
        return $this->gameRepository->save($game);
    }
    
    public function makeMove($gameId, $userId, $position) {
        
    }
    
    public function getStatus($gameId) {
        
    }
}
