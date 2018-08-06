<?php

namespace CensorCleaner\CheckQuility;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\CheckQuility
* 
*/


class ChForLimitSpecial implements IntChQuility
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
            $count_special = $this->check_prepear->countSymbols($this->config->config['langs']['exclude_preg'].'\s');
            $string_length = $this->check_prepear->countLength();
            /** Max percent of repeated words**/
            Clean::$log->debug('[ChForLimitSpecial] '.($count_special/$string_length).' > 0.25');
            if($count_special/$string_length > 0.25){
                Clean::$log->notice('[ChForLimitSpecial] BANED_RETARD $count_special/$string_length: '.($count_special/$string_length).' - "'.$this->config->msg_clean);
                return Checker\Checker::BANED_RETARD_SPECIAL;
            }
        }
        return FALSE;
    }
}  