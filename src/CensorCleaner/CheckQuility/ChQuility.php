<?php

namespace CensorCleaner\CheckQuility;

/**
* @category CleanTalk
* @package CensorCleaner\CheckQuility
* 
* Chain of responsibility Pattern used
* 
*/

class ChQuility implements IntChQuility
{
    /**
     * @var IntChQuility[]
     */
    protected $handlers = [];
    /**
    * Running Chain
    * 
    * @param mixed $message
    */
    public function checkQuility($message)
    {
        foreach ($this->handlers as $handler) {
            if($return = $handler->checkQuility($message)){ 
                return array('code' => $return, 'type' => $handler->type_cookie);
            }
        }
        return FALSE;
    }
    /**
    * Adding objects to chain
    * 
    * @param IntChQuility $handler
    */
    public function addHandler(IntChQuility $handler)
    {
        $this->handlers[] = $handler;
    }
}
