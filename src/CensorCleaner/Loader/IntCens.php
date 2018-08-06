<?php
  
namespace CensorCleaner\Loader;

/**
* @category CleanTalk
* @package CensorCleaner\Loader
* 
*/

interface IntCens
{
    /**
    * Get Censor Words, returning format array('word' => 'replacement')
    * @return array
    * 
    */
    static function getCens();
}