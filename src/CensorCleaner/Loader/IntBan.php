<?php
  
namespace CensorCleaner\Loader;

/**
* @category CleanTalk
* @package CensorCleaner\Loader
* 
*/

interface IntBan
{
    /**
    * Get Ip Ban, returning format array('ip' => '')
    * @return array
    * 
    */
    static function getBan();
}