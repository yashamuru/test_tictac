<?php

namespace TicTac\Domain;

interface GameRepositoryInterface
{    
    public function findById($id);
    public function save(Game $game);    
}
