<?php

namespace CensorCleaner\Filter;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\Filter
* 
*/

class FilterSwapList implements IntFiltering
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
        //    $this->config->config['replace']['cens_list']
        //    $this->config->config['langs']['exclude_preg']
        if ($usr_level <= $this->weight) {
            foreach($this->config->config['replace']['swap_list'] as $kl => $cword){
                $msg = preg_replace('#'.$kl.'#iu', $cword, $msg, -1);
            }
        }
        return $msg;
    }
}  