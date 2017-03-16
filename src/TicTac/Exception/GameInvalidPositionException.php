<?php

namespace TicTac\Exception;

class GameInvalidPositionException extends \Exception
{
    public function __construct($position) {
        $message = sprintf("Position %s is invalid or occupied", $position);
        parent::__construct($message);
    }
}
