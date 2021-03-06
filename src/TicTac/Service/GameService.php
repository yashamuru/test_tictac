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
        $player = $this->userRepository->findById($userId);
        if ( ! $player) {
            throw new EntityNotFoundException("User", $userId);
        }
        
        $game = $this->gameRepository->findById($gameId);
        if ( ! $game) {
            throw new EntityNotFoundException("Game", $gameId);
        }
        
        $game->makeAMove($userId, $position);
        $this->gameRepository->save($game);
        return true;
    }
    
    public function getStatus($gameId) {
        $game = $this->gameRepository->findById($gameId);
        if ( ! $game) {
            throw new EntityNotFoundException("Game", $gameId);
        }
        
        return [
            'finished' => $game->hasFinished(),
            'winnerId' => $game->getWinnerId() 
        ];
    }
}
