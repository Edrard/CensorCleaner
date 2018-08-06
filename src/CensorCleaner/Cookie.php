<?php

namespace CensorCleaner;

/**
* @category CleanTalk
* @package CensorCleaner\Checker
* 
*/

Class Cookie extends AbstractCookie
{
    /** 
    * Remember that $this->domain and $this->no_action presseting in __construct fucntion
    */
    /**
    * Setting user Cookies
    * 
    * @param string $name
    * @param string $data
    * @param numeric $ttl
    * @param boolean $encode
    */
    public function setUserCookie($name,$data,$ttl = 600,$encode = TRUE){
        if(!$this->no_action){
            $encode ? $data = base64_encode($data) : '';
            setcookie($name, $data, time()+$ttl,'/',$this->domain);
        }    
    }
    /**
    * Reading user Cookie
    * 
    * @param string $name
    * @param boolean $encode
    * @param mixed $return
    * @return string|mixed
    */
    public function getUserCookie($name,$encode = TRUE,$return = 0){
        if(isset($_COOKIE[$name])){ 
            return $encode ? base64_decode($_COOKIE[$name]) : $_COOKIE[$name];
        }else{ 
            return  $return;
        }
    }
}