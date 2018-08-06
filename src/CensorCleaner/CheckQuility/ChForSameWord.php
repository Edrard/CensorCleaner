<?php

namespace CensorCleaner\CheckQuility;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\CheckQuility
* 
*/


class ChForSameWord implements IntChQuility
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
            $count_by_type = $this->check_prepear->countPerWord();
            $count_word_total = $this->check_prepear->countWords();
            /** Max percent of repeated words**/
            $chwp = $this->config($count_word_total);
            foreach($count_by_type as $word => $count){
                Clean::$log->debug('[ChForSameWord] '.$word.' - '.($count/$count_word_total).' > '.$chwp);
                if($count/$count_word_total > $chwp){
                    Clean::$log->notice('[ChForSameWord] BANED_RETARD $count/$count_word_total: '.($count/$count_word_total).' > '.$chwp.' '.$this->config->msg_clean);
                    return Checker\Checker::BANED_RETARD_SAME_WORD;
                }
            }
        }
        return FALSE;
    }
    /**
    * Limit calculating
    * 
    * @param integer|float $count_word_total
    * @return integer|float
    */
    private function config($count_word_total){
        switch ($count_word_total) {
            case ($count_word_total == 1):
                return 2;
                break;
            case ($count_word_total > 10):
                return 0.35;
                break;
            case ($count_word_total > 5):
                return 0.40;
                break;
            case ($count_word_total == 5):
                return 0.45;
                break;
            case ($count_word_total == 4):
                return 0.5;
                break;
            case ($count_word_total == 3):
                return 0.75;
                break;
            case ($count_word_total == 2):
                return 2;
                break;
            default:
                return 0.35;
        }
    }
}  
