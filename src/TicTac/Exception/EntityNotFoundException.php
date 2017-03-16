<?php

namespace TicTac\Exception;

class EntityNotFoundException extends \Exception
{
    public function __construct($entityName, $id) {
        $message = sprintf("Cannot find %s with id: %s", $entityName, $id); 
        parent::__construct($message);
    }
}
