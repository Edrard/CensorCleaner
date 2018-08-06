<?php

namespace CensorCleaner\Filter;

/**
* @category CleanTalk
* @package CensorCleaner\CheckQuility
* 
* Chain of responsibility Pattern used
* 
*/

class Filtering implements IntFiltering
{
    /**
    * @var IntChQuility[]
    */
    protected $handlers = [];
    /**
    * Running filter class
    * 
    * @param mixed $lvl
    * @param mixed $message
    */
    public function filter($lvl,$message)
    {
        foreach ($this->handlers as $handler) {
            $message = $handler->filter($lvl,$message);
        }

        return $message;
    }
    /**
    * Adding filter objects
    * 
    * @param IntFiltering $handler
    */
    public function addHandler(IntFiltering $handler)
    {
        $this->handlers[] = $handler;
    }
}
