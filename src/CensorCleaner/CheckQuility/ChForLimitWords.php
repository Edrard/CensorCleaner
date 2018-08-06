<?php

namespace CensorCleaner\CheckQuility;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\CheckQuility
* 
*/


class ChForLimitWords implements IntChQuility
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
    /**
    * Latters limit 
    * 
    * @var mixed
    */
    public $limit  = 1000;
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
            $length = $this->check_prepear->countLength();   //Line count
            Clean::$log->debug('[ChForLimitWords] '.($length).' > '.$this->limit);
            if($length > $this->limit){
                Clean::$log->notice('[ChForLimitWords] BANED_RETARD_AMMOUNT_WORDS $lines: '.($length).' > '.$this->limit.' '.$this->config->msg_clean);
                return Checker\Checker::BANED_RETARD_AMMOUNT_WORDS;
            }
        }
        return FALSE;
    }
}  