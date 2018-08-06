<?php

namespace CensorCleaner\CheckQuility;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;

/**
* 
* Checking if Message have less blanks then in $this->config()
* 
* @category CleanTalk
* @package CensorCleaner\CheckQuility
* 
*/


class ChForLimitBlank implements IntChQuility
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
            $length = $this->check_prepear->countLength();
            /** Max percent of repeated words**/
            $chwp = $this->config($length);
            if(isset($count_by_type[' '])){
                $count_by_type[' '] = $count_by_type[' '] ? $count_by_type[' '] : 0;
                Clean::$log->debug('[ChForLimitBlank] '.$count_by_type[' '].' - '.($count_by_type[' ']/$length).' <= '.$chwp);
                if($count_by_type[' ']/$length <= $chwp){
                    Clean::$log->notice('[ChForLimitBlank] BANED_RETARD $count_by_type[\' \']/$length: '.($count_by_type[' ']/$length).' <= '.$chwp.' '.$this->config->msg_clean);
                    return Checker\Checker::BANED_RETARD_BLANK;
                }
            }
        }
        return FALSE;
    }
    /**
    * Limit calculating method
    * 
    * @param mixed $length
    * @return mixed
    */
    private function config($length){
        switch ($length) {
            case ($length > 20 && $length <= 30):
                return 0.055;
                break;
            case ($length > 30 && $length <= 40):
                return 0.06;
                break;
            case ($length > 40 ):
                return 0.065;
                break;
            default:
                return -1;
        }
    }
}  