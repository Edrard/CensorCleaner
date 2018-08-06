<?php

namespace CensorCleaner\CheckQuility;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;

/**
* Censor checking class
* 
* @category CleanTalk
* @package CensorCleaner\CheckQuility
* 
*/


class ChForCensorMin implements IntChQuility
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
            $censor_count = $this->check_prepear->countCensor($this->config->config['replace']['cens_list']);
            $length = $this->check_prepear->countOnlyLatters($this->config->config['langs']['exclude_preg']);
            $censor_count_exclude = $this->check_prepear->countCensorExclude($this->config->config['langs']['exclude_filter']);
            $censor_count -= $censor_count_exclude;
            /** Min limit of Censor word **/
            $chwp = $this->config($length);
            Clean::$log->debug('[ChForCensorMin] '.$chwp.' < '.$censor_count);
            if($censor_count > 0){
                $censor_word_list = $this->check_prepear->censoredWords($this->config->config['replace']['cens_list']);
                Clean::$log->notice('[ChForCensorMin]: '.implode(", ",$censor_word_list));
            }
            if($chwp <= $censor_count){
                Clean::$log->notice('[ChForCensorMin] BANED_CENSOR_LIMIT $chwp < $censor_count: '.$chwp.' < '. $censor_count.' - "'.$this->config->msg_clean);
                return Checker\Checker::BANED_CENSOR_LIMIT;    
            }
        }
        return FALSE;
    }
    /**
    * Limit calculating method
    * 
    * @param mixed $length
    */
    private function config($length){
        return 1 + round($length/300);
    }
}  