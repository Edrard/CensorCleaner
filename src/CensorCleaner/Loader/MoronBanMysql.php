<?php

namespace CensorCleaner\Loader;

use CensorCleaner\Helper;

/**
* @category CleanTalk
* @package CensorCleaner\Loader
* 
*/

Class MoronBanMysql implements IntBan
{
    /**
    * Get Censor Words, returning format array('word' => 'replacement')
    * @return array
    * 
    */
    static function getBan(){
        return Helper::array_2d(
            \CensorCleaner\CleanTalk::$getter["mysql"]->get_data(array('query'=>"SELECT * FROM ban__moron", 'multi' => TRUE))
            ,'','value');
    }
}