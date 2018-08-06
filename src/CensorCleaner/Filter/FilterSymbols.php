<?php

namespace CensorCleaner\Filter;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\Filter
* 
*/

class FilterSymbols implements IntFiltering
{
    /**
    * Min weight to run this class
    * 
    * @var mixed
    */
    public $weight = '1';
    /**
    * CleanTalk config
    * 
    * @var \CensorCleaner\Config
    */
    public $config;
    /**
    * Filters Countings
    * 
    * @var IntFilterExcluder
    */
    private $excluder;
    function __construct(\CensorCleaner\Config $config, IntFilterExcluder $excluder, $weight){
        $this->weight        = $weight;
        $this->config        = $config;
        $this->excluder      = $excluder;
    }
    /**
    * Filtering method
    * 
    * @param integer $usr_level
    * @param string $msg
    * 
    * @return string - filtered message
    */
    public function filter($usr_level,$msg)
    {
        if ($usr_level <= $this->weight) {
            $msg = preg_replace("/\.+/u", ".", $msg);
            $msg = preg_replace("/\!+/u", "!", $msg);
            $msg = preg_replace("/\?+/u", "?", $msg);
        }
        return $msg;
    }
}  