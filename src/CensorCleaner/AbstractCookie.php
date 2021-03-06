<?php

namespace CensorCleaner;

/**
* @category CleanTalk
* @package CensorCleaner
* 
*/

abstract Class AbstractCookie
{
    /**
    * Domain var
    * 
    * @var string
    */
    public $domain;
    /**
    * put your comment there...
    * 
    * @var boolean TRUE use Cookie, FALSE - no Cookie
    */
    public $no_action = FALSE;
    /**
    * Presseting domain on inicialization
    * 
    * @param mixed $domain
    */
    function __construct($domain,$no_action){
        $this->no_action = $no_action;
        $this->domain    = $domain;    
    }    
    public abstract function setUserCookie($name,$data,$ttl,$encode);
    public abstract function getUserCookie($name,$encode);
}