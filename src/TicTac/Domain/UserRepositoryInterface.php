<?php

namespace TicTac\Domain;

interface UserRepositoryInterface
{    
    public function findById($id);
    public function save(User $user);    
}
