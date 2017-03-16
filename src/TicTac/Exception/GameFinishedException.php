<?php

namespace TicTac\Exception;

class GameFinishedException extends \Exception
{
    public function __construct($message = 'Game is already finished') {
        parent::__construct($message);
    }
}
