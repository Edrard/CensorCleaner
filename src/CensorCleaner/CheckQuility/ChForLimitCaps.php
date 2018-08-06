<?php

namespace CensorCleaner\CheckQuility;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\CheckQuility
* 
*/


class ChForLimitCaps implements IntChQuility
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
            $length = $this->check_prepear->countLength();   //Line count
            $upper = $this->check_prepear->countUpperCase($this->config->config['langs']['upper_case_preg']);
            $words = $this->check_prepear->countWords();
            $cw = $this->config($words);
            Clean::$log->debug('[ChForLimitCaps] '.($upper/$length).' > '.$cw);
            if($upper/$length > $cw){
                Clean::$log->notice('[ChForLimitCaps] BANED_RETARD_CAPS $lines: '.($length).' > '.$cw.' '.$this->config->msg_clean);
                return Checker\Checker::BANED_RETARD_CAPS;
            }
        }
        return FALSE;
    }
    /**
    * Limit calculating
    * 
    * @param integer|float $total_type
    * @return integer|float
    */
    private function config($words){
        switch ($words) {
            case ($words == 1):
                return 0.35;
                break;
            case ($words == 2):
                return 0.15;
                break;
            case ($words == 3):
                return 0.11;
                break;
            default:
                return 0.1;
        }
    }
}  