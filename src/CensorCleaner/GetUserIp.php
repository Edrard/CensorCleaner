<?php

namespace CensorCleaner;

/**
* @category CleanTalk
* @package CensorCleaner\Checker
* 
*/

Class GetUserIp implements IntGetUserIp
{
    /**
    * Header fields
    * 
    * @var array
    */
    protected static $array = array('HTTP_X_FORWARDED_FOR','HTTP_CLIENT_IP','REMOTE_ADDR','HTTP_VIA');
    protected static $ip = NULL;
    /**
    * Getting user IP
    * 
    * @param array $custom additional header fields
    * 
    * @return string|false 
    */
    static function getIp($custom = array()){   
        if(static::$ip == null){
            !empty($custom) ? static::$array = array_merge($custom,$array) : '';
            $func = $_SERVER ? '_globalArray' : '_getEnv';
            foreach(static::$array as $type){
                if(static::$ip = self::$func($type)){
                    return static::$ip;
                }
            }
            return static::$ip = FALSE;
        }
        return static::$ip;
    }
    /**
    * Checking global $_SERVER global array
    * 
    * @param string $type header field
    */
    private static function _globalArray($type){
        return isset($_SERVER[$type]) ? $_SERVER[$type] : FALSE;        
    }
    /**
    * Checking getenv
    * 
    * @param string $type header field
    */
    private static function _getEnv($type){
        return  getenv($type) ? getenv($type) : FALSE;
    }
}