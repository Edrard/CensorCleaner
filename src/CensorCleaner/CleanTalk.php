<?php

namespace CensorCleaner;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use CensorCleaner\Cache;
use CensorCleaner\Loader;

/**
* @category CleanTalk
* @package CensorCleaner
*/

Class CleanTalk 
{
    /**
    * Config Store var
    * 
    * @var Config
    */
    static $config;
    /**
    * Cache store var
    * 
    * @var mixed
    */
    static $cache;
    /**
    * Loging Class
    */
    static $log;
    /**
    *  Checker Class
    * 
    *  $var object
    */
    private static $checker;
    /**
    * Filter Class
    * 
    * @var object
    */
    private static $filter;
    /**
    * Getting Data class
    * 
    * @var object implements IntGetter
    */
    static $getter;
    /**
    * Json Config reader
    * 
    * @param mixed $json_config
    */
    static $file_config; 
    function __construct($log_level=Logger::INFO, $config_types = array('main_class' => 'Loader\\FileConfig', 'class' => 'Loader\\JsonFile', 'path' => 'Config') ){
        try {
            $class = __NAMESPACE__.'\\'.$config_types['class'];
            $main_class = __NAMESPACE__.'\\'.$config_types['main_class'];
            /** Setting Config directory **/
            $dir = dirname(__FILE__).'/'.$config_types['path'];
            /** Config Reader Class **/
            static::$file_config = new $main_class(new $class($dir));
            /** Checking Config reader Class **/
            if(!static::$file_config instanceof Loader\IntConfigWorker){
                throw new BaseException('Wrong Interface for Config Class');
            }
            /** Reading Config **/
            $this->readCleanTalkConfig();
            /*Starting Monolog*/     
            $log = new Logger('clean');
            /** $this->log_path setted in CleanTalk.json **/
            //TODO: Add Path checker
            $this->log_path = $_SERVER['DOCUMENT_ROOT'].'/'.$this->log_path.'/';
            $log->pushHandler(new StreamHandler($this->log_path.'your_'.date('d-m-Y').'.log', $log_level ));
            /* Log Initialization */
            $this->setLogs($log);
            static::$log->debug('Monolog Setted');

            static::$checker = new Checker\Checker();
            static::$filter  = new Filter\Filter();
            /** Setting Connections **/
            foreach($this->getters as $key => $getter){
                $class = __NAMESPACE__.'\\'.$getter;
                static::$getter[$key] = new $class();  
                /** Interface Checcking **/
                if(!static::$getter[$key] instanceof IntGetterSetter){
                    throw new BaseException('Wrong Interface for '.$key);
                }
            }
            /* Cache Initialization */
            $this->setCache($this->cache_types);
            static::$log->debug($this->cache_types['class'].' cache setted');  
        } catch( BaseException $e){
            static::$log->err($e->getMessage());
            echo $e->getMessage(); die;   
        }

    } 
    // TODO: MOVE IT TO Config class
    /**
    * Preset function, $cens need to build exclude combination and can be change on init 
    * 
    *  $msg - Message to check
    *  $user_lvl - User Leve
    *  $cens = word of what we changing rudes
    *  $no_action - not setting Cookie
    *  $cache_types - cache parametrs
    * 
    * @param string $msg
    * @param numeric $user_lvl
    * @param string $cens
    * @param boolean $no_action
    * @param array $cache_types
    */
    function presetConfig($msg,$user_lvl,$domain,$no_cookie = FALSE){
        try {
            /* Config Initialization */     
            static::$config = Config::getInstance(); 
            /**Setting User IP **/
            static::$config->ip = GetUserIp::getIp(); 
            /** Setting Domain **/
            static::$config->domain = $domain;
            static::$log->debug('Domain - '. static::$config->domain);
            /** Main cens replacer **/
            static::$config->cens = $this->cens;
            static::$log->debug('Cens replace - '.$this->cens);
            /** Setting Message **/
            static::$config->msg = $msg;
            static::$log->debug('Cens message - '.$msg);   
            /** Lang Init **/ 
            static::$config->setupLangs($this->lang);
            static::$log->debug('Langs - '.$this->lang);
            /** User Level **/
            static::$config->config['user_lvl'] = $user_lvl;
            static::$log->debug('User Level - '.$user_lvl);
            /** Cookie use **/                               
            static::$config->config['no_action'] = $no_cookie;
            static::$log->debug('No action state - '.$no_cookie);
            /** Censer list Init $this->cens_class in CleanTalk.json **/ 
            static::$config->setupList($this->cens_class);       
            /** Setting temporary ban file path from CleanTalk.json **/ 
            static::$config->tmp_file_path = $_SERVER['DOCUMENT_ROOT'].'/'.$this->tmp_file_path.'/';  
        } catch( BaseException $e){
            static::$log->err($e->getMessage());
            echo $e->getMessage(); die;   
        }
    }
    /**
    * Reading main CleanTalk Config
    * 
    * @param string $file - Filename  
    */
    private function readCleanTalkConfig($file = 'CleanTalk.json'){
        $config = $this->_readConfigFile($file);
        foreach($config as $key => $val){
            $this->$key = $val;    
        }  
    }
    /**
    * Read Userban Config File
    * 
    * @param string $file - Filename 
    */
    private function readUserBanConfig($file = 'UserBan.json'){
        return $this->_readConfigFile($file); 
    }
    /**
    * Read Checker Config File
    * 
    * @param mixed $file - Filename
    */
    private function readCheckerConfig($file = 'Checker.json'){
        return $this->_readConfigFile($file); 
    }
    /**
    * Read Checker Config File
    * 
    * @param mixed $file - Filename
    */
    private function readFilterConfig($file = 'Filter.json'){
        return $this->_readConfigFile($file); 
    }
    /**
    * Read Config file
    * 
    * @param string $file - file 
    */
    // TODO: Make it Cachable
    private function _readConfigFile($file){
        if(!$config = static::$file_config->readConfig($file)){
            throw new BaseException('Cant read '.$file.' File');    
        }
        return $config;    
    }
    /**
    * Run Message Filter
    * 
    * @return string - Filtetred message
    *  
    */
    public function filterMessage(){
        if(static::$config->msg){
            /** Checking of status of Filter **/
            if($this->state['filter'] != 'on'){
                return static::$config->msg;
            }
            try {
                static::$filter->getPrepea(
                    static::$config,
                    new Filter\FilterExcluder(static::$config->msg, static::$config->config['langs']['exclude_filter']),
                    $this->readUserBanConfig(),
                    $this->readFilterConfig() 
                );
                return static::$filter->run();
            } catch( BaseException $e){
                static::$log->err($e->getMessage());
                echo $e->getMessage(); die;   
            }           
        }
        return '';    
    }
    /**
    * Cleaning Message for Quility Checking
    * 
    * @param string $msg - if not setted, then static::$config->msg must be setted
    */
    public function preCleanMessage($msg = ''){
        /** Checking for empty message **/
        if(!$msg){
            if(isset(static::$config->msg)) {
                $msg =  static::$config->msg; 
            }else{
                throw new BaseException('No message setted, pls run first presetConfig(), or send $msg');
            }
        }
        $pre_clean = new PreCleanMessage($msg, new Loader\ListOfExclude(),$this->list_of_exclude,$this->strip_tags);
        /** Clean Message **/
        return static::$config->msg_clean = $pre_clean->cleanMessage();
    }
    /**
    * Return ammount of censored word in text
    * 
    * @return integer
    */
    public function countCensors(){
        $this->preCleanMessage();
        $checker = new Checker\CheckerPrepear(static::$config->msg_clean, static::$config->msg); 
        return $checker->countCensor(static::$config->config['replace']['cens_list']);  
    }
    /**
    * Run Message Checker
    * 
    * @return message status
    *  
    */
    public function checkMessage(){
        if(static::$config->msg){
            /** Checking for status of Checker **/
            if($this->state['checker'] != 'on'){
                return 0;
            }
            try {
                /** Clean Message **/
                $this->preCleanMessage();
                static::$checker->getPrepea(
                    static::$config, 
                    new Checker\CheckerPrepear(static::$config->msg_clean, static::$config->msg), 
                    new GetContentCurl(),
                    new Checker\BanUser(
                        new Loader\FileTmpBanUsers( 
                            new Loader\JsonFile(static::$config->tmp_file_path)
                        ),
                        new Cookie(
                            static::$config->domain,
                            static::$config->config['no_action']
                        ),
                        GetUserIp::getIp(),
                        /** UserBan Config Read **/
                        $this->readUserBanConfig(),
                        static::$config->config['user_lvl'] 
                    ),
                    $this->readCheckerConfig()
                );
                return static::$checker->run();
            } catch( BaseException $e){
                static::$log->err($e->getMessage());
                echo $e->getMessage(); die;   
            }           
        }
        return -1;    
    }
    /**
    * Setting Cache Class, to File cache possible to set path
    * 
    * @param mixed $cache
    */
    private function setCache($cache_types){
        if(!isset($cache_types['class'])){
            throw new BaseException('No cache class setted');    
        }
        $in_class = __NAMESPACE__.'\\Cache\\'.$cache_types['class'];
        static::$cache = new $in_class($_SERVER['DOCUMENT_ROOT'].'/'.$cache_types['path']);
        /* Throw exception if wrong Interface */
        if(!static::$cache instanceof Cache\IntCache){  
            throw new BaseException('Wrong Cache used');    
        }
    }
    /**
    * Set Logger
    * 
    * @param mixed $log
    */
    private function setLogs($log){
        static::$log = $log;
    }

    /**
    * Change Message
    * 
    * @param mixed $msg
    */
    public function changeMessage($msg){
        static::$config->msg = $msg;    
    } 
    /**
    * Change User Level
    * 
    * @param mixed $user_lvl
    */
    public function changeUserLevel($user_lvl){
        static::$config->config['user_lvl'] = $user_lvl; 
    }
    /**
    * Change noAction
    * 
    * @param mixed $no_action
    */
    public function noAction($no_action){
        static::$config->config['no_action'] = $no_action; 
    }
    /**
    * change Language pack
    * 
    * @param mixed $langs
    * 
    */
    public function changeLangs($lang){
        static::$config->setupLangs($lang);  
    }
    /**
    * Presset All Bans
    * 
    */
    public function pressetAllBans(){
        static::$config->presetBans($this->ip_ban_class, 'ip_ban');
        static::$config->presetBans($this->link_ban_class, 'link_ban');
        static::$config->presetBans($this->moron_ban_class, 'moron_ban');
        static::$config->presetBans($this->you_ban_class, 'you_ban'); 
    }
    /**
    * Set External Ip Bans
    *   
    * @param mixed $ip_ban
    */
    public function setIpBan($ip_ban){
        static::$config->presetBans($this->ip_ban_class, 'ip_ban', $ip_ban);
    }
    /**
    * Set External Links Bans
    *   
    * @param mixed $ip_ban
    */
    public function setLinkBan($link_ban){
        static::$config->presetBans($this->link_ban_class, 'link_ban', $link_ban);
    }
    /**
    * Set External Moron Bans
    *   
    * @param mixed $ip_ban
    */
    public function setMoronBan($moron_ban){
        static::$config->presetBans($this->moron_ban_class, 'moron_ban', $moron_ban);
    }
    /**
    * Set External Youtube Bans
    *   
    * @param mixed $ip_ban
    */
    public function setYouBan($you_ban){
        static::$config->presetBans($this->you_ban_class, 'you_ban', $you_ban);
    }
    /**
    * Set External Censor List
    *   
    * @param mixed $ip_ban
    */
    public function setCens($cens_list){
        static::$config->setupList($this->cens_class,$cens_list);
    }
}
/**
* EMPTY_MSG - Empty message
*/