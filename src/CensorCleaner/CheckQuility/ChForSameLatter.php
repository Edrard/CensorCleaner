<?php

namespace CensorCleaner\CheckQuility;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\CheckQuility
* 
*/


class ChForSameLatter implements IntChQuility
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
            $count_by_type = $this->check_prepear->countPerLatter();
            $string_length = $this->check_prepear->countLength();
            /** Max percent of repeated latters**/
            $chwp = $this->config(count($count_by_type));
            foreach($count_by_type as $latter => $count){
                if($latter != ' '){
                    Clean::$log->debug('[ChForSameLatter] '.$latter.' - '.($count/$string_length).' > '.$chwp);
                    if($count/$string_length > $chwp){
                        Clean::$log->notice('[ChForSameLatter] BANED_RETARD $count/$string_length: '.($count/$string_length).' > '.$chwp.' '.$this->config->msg_clean);
                        return Checker\Checker::BANED_RETARD_SAME_LATTER;
                    }
                }
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
    private function config($total_type){
        switch ($total_type) {
            case ($total_type > 30):
                return 0.25;
                break;
            case ($total_type > 20):
                return 0.30;
                break;
            case ($total_type > 10):
                return 0.35;
                break;
            default:
                return 0.4;
        }
    }
}  
