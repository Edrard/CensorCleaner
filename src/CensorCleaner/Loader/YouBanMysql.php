<?php

namespace CensorCleaner\Loader;

use CensorCleaner\Helper;

/**
* @category CleanTalk
* @package CensorCleaner\Loader
* 
*/

Class YouBanMysql implements IntBan
{
    /**
    * Get Censor Words, returning format array('word' => 'replacement')
    * @return array
    * 
    */
    static function getBan(){
        return Helper::array_2d(
            \CensorCleaner\CleanTalk::$getter["mysql"]->get_data(array('query'=>"SELECT * FROM ban__you", 'multi' => TRUE))
            ,'','value');
    }
}