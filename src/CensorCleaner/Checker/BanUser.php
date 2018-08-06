<?php

namespace CensorCleaner\Checker;

use CensorCleaner\Loader;
use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\Checker
* 
*/

Class BanUser implements IntBanUser
{
    /**
    * List of Cookie types and starting ammount of fails
    * 
    * @var array
    */
    public $cookie_name = array('phpbb_intq' => 0, 'phpbb_intm' => 0);
    /**
    * User state
    * 
    * @var boolean
    */
    public $user_banned = FALSE;
    /**
    * Type of user ban
    * 
    * @var string|FALSE
    */
    public $user_banned_type = FALSE;
    /**
    * UserBan.json config file
    * 
    * @var array
    */
    public $config;
    /**
    * Cookie class
    * 
    * @var \CensorCleaner\AbstractCookie
    */
    private $cookie;
    /**
    * Temporary ban list class
    * 
    * @var Loader\IntTmpBanWorker
    */
    private $tmp_list;
    /**
    * User Ip
    * 
    * @var string
    */
    private $ip;
    /**
    * User level
    * 
    * @var string
    */
    private $user_level;
    /**
    * Readed bans from temporary list class
    * 
    * @var mixed
    */
    private $file = array();

    function __construct(Loader\IntTmpBanWorker $tmp_list, \CensorCleaner\AbstractCookie $cookie , $ip, array $config, $user_level){
        $this->ip          = $ip;
        $this->cookie      = $cookie;    
        $this->tmp_list    = $tmp_list;
        $this->user_level  = $user_level;
        $this->config      = $config;
        if($file = $this->tmp_list->readTmpBanUsers()){
            $this->file = $file;    
        }
        $this->checkUserStatus();
    }
    /**
    * Checking user status
    *   
    */
    function checkUserStatus(){
        $this->user_banned = FALSE;
        if($this->ip !== FALSE){
            if(!empty($this->file)){
                /** checking if user ip banned **/
                if(isset($this->file[$this->ip])){
                    if($this->file[$this->ip]['time'] + $this->file[$this->ip]['ttl'] >= time()){
                        $this->user_banned = TRUE; 
                        $this->user_banned_type = $this->file[$this->ip]['type'];
                        Clean::$log->debug(' --- Ip All Ready Banned --- '.$this->ip);   
                    }else{
                        /** If time of ban ended, then removing user ip and daving ban list**/
                        unset($this->file[$this->ip]);
                        $this->saveBan();
                        Clean::$log->debug('Removing IP: '.$this->ip.' and saving file. BanTime done');
                    }
                }
            }
        }
        if($this->user_banned === FALSE){ 
            Clean::$log->debug('Ip Not banned Right now '.$this->ip);
            /** Checking user Cookie stat and sending time to ban to setUserStatus() method**/
            $this->setUserStatus($this->getCookieBan());    
        }
    }
    /**
    * Geting User status 
    * @return boolean
    * 
    */
    function getUserStatus(){
        return $this->user_banned;    
    } 
        /**
    * Geting User type ban
    * @return FALSE|string
    * 
    */
    function getUserBanType(){
        return $this->user_banned_type;    
    } 
    /**
    * Adding Cookie Warning
    * 
    * @param string $name - cookie name
    */
    function addCookieWarning($name){
        $data = $this->cookie_name[$name] + 1;
        Clean::$log->debug('Setting Cookie for '.$name.', ammount:'.$data.' No Action Status - '.($this->cookie->no_action !== FALSE ? 1 : 0));
        /** Setting warning**/
        $this->cookie->setUserCookie($name,$data,$this->config[$this->user_level]['cookie_time_'.$name]); 
        /** Recheking user status **/
        $this->checkUserStatus();
    }
    /**
    * Setting User ban to Temporary ban list if time != 0 and user_banned == TRUE
    * 
    * @param integer $time
    */
    function setUserStatus($user){
        $time = $user['time'];
        $type = $user['type'];
        if($this->user_banned !== FALSE && $time != 0){
            $this->file[$this->ip]['ttl'] = $time;
            $this->file[$this->ip]['time'] = time();
            $this->file[$this->ip]['type'] = $type;
            $this->saveBan();
            Clean::$log->debug('User was banned in'.$this->file[$this->ip]['time'].'for '.$this->file[$this->ip]['ttl']);
        }  
    }
    /**
    * Saving current $this->list to temporary ban list
    * 
    */
    private function saveBan(){
        $this->tmp_list->writeTmpBanUsers($this->file);
    }
    /**
    * Checking if Cookie ban exited limit and returning ban time 
    * 
    * @return integer, default 0
    */
    private function getCookieBan(){
        $time = 0;
        foreach($this->cookie_name as $type => &$count){
            $count  = $this->cookie->getUserCookie($type);  
            /** U is Unlimited **/ 
            if($this->config[$this->user_level][$type] != 'U'){
                /** Checking limit**/
                if($count > $this->config[$this->user_level][$type]){ 
                    $time = max($time, $this->config[$this->user_level]['time_'.$type]); 
                    Clean::$log->debug('Cookie Count more then '.$count.' for '.$type);
                    /** if limit exited then setting to ban **/
                    $this->user_banned = TRUE;
                    $this->user_banned_type = $type;
                } 
            }
        }
        return array('time' => $time, 'type' => $this->user_banned_type);
    }
}