<?php

namespace TicTac\Exception;

class GameNotYourTurnException extends \Exception
{
    public function __construct($message = "It\'s not your turn") {
        parent::__construct($message);
    }
}
