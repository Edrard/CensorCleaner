<?php

namespace CensorCleaner\CheckQuility;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\CheckQuility
* 
*/


class ChForLimitLinks implements IntChQuility
{
        /**
    * Type of ban
    * 
    * @var string
    */
    public $type_cookie;
    /**
    * Checker class
    * 
    * @var Checker\IntCheckerPrepear
    */
    private $check_prepear;
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
    function __construct(\CensorCleaner\Config $config, Checker\IntCheckerPrepear $check_prepear, $weight,$type_cookie){
        $this->type_cookie         = $type_cookie;
        $this->check_prepear = $check_prepear;
        $this->weight        = $weight;
        $this->config        = $config;
    }
    /**
    * Checking Quility of message
    * 
    * @param integer $usr_level
    * @return integer|false - status of filter
    */
    public function checkQuility($usr_level)
    {
        if ($usr_level <= $this->weight) {
            $links = $this->check_prepear->countLinks($this->config->domain);   //Line count
            Clean::$log->debug('[ChForLimitLinks] '.($links).' > 5');
            if($links > 100){
                Clean::$log->notice('[ChForLimitLinks] BANED_RETARD $lines: '.($links).' > 100 '.$this->config->msg);
                return Checker\Checker::BANED_RETARD_LINKS;
            }
        }
        return FALSE;
    }
}  