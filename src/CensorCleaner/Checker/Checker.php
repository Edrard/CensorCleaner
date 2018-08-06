<?php

namespace CensorCleaner\Checker;

/**
* @category CleanTalk
* @package CensorCleaner\Checker
* 
*/

Class Checker
{
    /**
    * No probles
    */
    const GOOD = 0;
    /**
    * Banned in tmp file
    */
    const BANED_RETARD = 100;
    /**
    * Too many blank
    */
    const BANED_RETARD_BLANK = 101;
    /**
    * Too many Lines
    */
    const BANED_RETARD_LINES = 102;
    /**
    * Too many links
    */
    const BANED_RETARD_LINKS = 103;
    /**
    * Too many Special
    */
    const BANED_RETARD_SPECIAL = 104;
    /**
    * Too many same latters
    */
    const BANED_RETARD_SAME_LATTER = 105;
    /**
    * Too many same Words
    */
    const BANED_RETARD_SAME_WORD = 106;
    /** 
    * Too many latters
    */
    const BANED_RETARD_AMMOUNT_WORDS = 107;
    /** Too many Caps **/
    const BANED_RETARD_CAPS = 108;
    /**
    * Banned IP in global list
    */
    const BANED_RETARD_IP_BAN = 111;
    /**
    * Banned Link in global list
    */
    const BANED_RETARD_LINK_BAN  = 112;
    /**
    * Banned URL in global list
    */
    const BANED_RETARD_MORON_BAN = 113;
    /**
    * Banned Youtube ID in global list
    */
    const BANED_RETARD_YOU_BAN = 114;
    /**
    * Banned Link on linked site
    */
    const BANED_RETARD_CURL_LINK_BAN  = 122;
    /**
    * Banned URL on linked site
    */
    const BANED_RETARD_CURL_MORON_BAN = 123;
    /**
    * Banned Youtube ID on linked site
    */
    const BANED_RETARD_CURL_YOU_BAN = 124;
    /**
    * Ban foe Censor
    */
    const BANED_CENSOR = 200;
    /**
    * Too many Censors word
    */
    const BANED_CENSOR_LIMIT = 201;

    /**
    * Config CleanTalk
    * 
    * @var \CensorCleaner\Config
    */
    private $config;
    /**
    * Class to check message
    * 
    * @var IntCheckerPrepear
    */
    private $counts;
    /**
    * Page getter class
    * 
    * @var {\CensorCleaner\IntGetContent|IntCookieBan}
    */
    private $url_get;
    /**
    * User status Class
    * 
    * @var IntBanUser
    */
    private $user;
    /** 
    * List of Checking classes form Checker.json
    * 
    * @var IntGetContent
    */
    private $checker_classes;
    /**
    * Prepearing checker
    * 
    * @param \CensorCleaner\Config $config
    * @param IntCheckerPrepear $check_prepear
    * @param \CensorCleaner\IntGetContent $url_get
    * @param IntBanUser $user
    * @param array $checker_classes
    */
    
    public function getPrepea(\CensorCleaner\Config $config, IntCheckerPrepear $check_prepear, \CensorCleaner\IntGetContent $url_get, IntBanUser $user, array $checker_classes){
        $this->config          = $config;
        $this->counts          = $check_prepear;
        $this->url_get         = $url_get;
        $this->user            = $user;
        $this->checker_classes = $checker_classes;
    }
    /**
    * Runing Checkers
    * 
    * @return integer - Status Code 
    */
    public function run(){
        if($this->user->getUserStatus() === FALSE){
            foreach($this->checker_classes as $type => $chains){
                $class = '\\CensorCleaner\\CheckQuility\\'.$type;
                $handler = new $class();
                foreach($chains as $type_cookie => $inner_classes){
                    foreach($inner_classes as $class_name => $min){
                        $ch_class = '\\CensorCleaner\\CheckQuility\\'.$class_name;
                        $handler->addHandler(new $ch_class($this->config,$this->counts,$min,$type_cookie));
                    }
                }
            }
            $state = $handler->checkQuility($this->user->config[$this->config->config['user_lvl']]['wieght']);
            if(isset($state['code'])){
                $this->user->addCookieWarning($state['type']);
                return $state['code'];   
            }
            unset($handler);
            return static::GOOD;
        }
        if($this->user->getUserBanType() == 'phpbb_intm'){
            return static::BANED_CENSOR;
        }else{
            return static::BANED_RETARD;   
        }
    }
}