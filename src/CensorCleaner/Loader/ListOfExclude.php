<?php

namespace CensorCleaner\Loader;

use CensorCleaner\Helper;
use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\Loader
* 
*/

Class ListOfExclude implements IntListOfExclude
{
    private $handlers = array();
    /**
    * Setuping exclude list, it must return regExp masks
    * @return array - regExp mask array 
    */
    function setupExcludeList(){
        $return = array();
        if(empty($this->handlers)){
            Clean::$log->debug('Exclude list Empty');    
            return $return;
        }
        $list = array();
        foreach($this->handlers as $hand){
            $list = Helper::array_special_merge($list,$hand->getList());    
        }
        if(is_array($list)){
            foreach($list as $inner){
                $inner = preg_quote($inner, '/');
                $return[] = "/\[$inner.*\]/iU";
                $return[] = "/\[\/$inner.*\]/iU";    
            }
        }
        return $return;
    }
    function setHendlers( IntGetSingleList $handler){
        $this->handlers[] = $handler; 
    }
}