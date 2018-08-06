<?php

namespace CensorCleaner\CheckQuility;

use CensorCleaner\Checker;

/**
* Checking Global list - links, url, youtube id on links
* 
* @category CleanTalk
* @package CensorCleaner\CheckQuility
* 
*/


class ChForBanCurl implements IntChQuility
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
    * Limit weight
    * 
    * @var mixed
    */
    public $weight = '1';
    /**
    * CleanTalk Config
    * 
    * @var \CensorCleaner\Config
    */
    public $config;
    /**
    * URL get class
    * 
    * @var \CensorCleaner\GetContentCurl
    */
    protected $url_get;
    function __construct(\CensorCleaner\Config $config, Checker\IntCheckerPrepear $check_prepear, $weight,$type_cookie){
        $this->type_cookie         = $type_cookie;
        $this->check_prepear = $check_prepear;
        $this->weight        = $weight;
        $this->config        = $config;
        $this->url_get          = new \CensorCleaner\GetContentCurl();
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
            $links = $this->check_prepear->extractLinks();
            foreach($links as $link){
                $web = $this->url_get->getUrl($link);
                foreach($this->config->config['moron_ban'] as $link){
                    if(preg_match('#'.$link.'#iu',$web)){
                        return Checker\Checker::BANED_RETARD_CURL_MORON_BAN;
                    }
                }
                foreach($this->config->config['link_ban'] as $link){
                    if(preg_match('#'.$link.'#iu',$web)){
                        return Checker\Checker::BANED_RETARD_CURL_LINK_BAN;
                    }
                }
                foreach($this->config->config['you_ban'] as $link){
                    if(preg_match('#'.$link.'#iu',$web)){
                        return Checker\Checker::BANED_RETARD_CURL_YOU_BAN;
                    }
                }
            }
        }
        return FALSE;
    }
}  