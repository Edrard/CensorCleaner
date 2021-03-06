<?php

namespace CensorCleaner\Loader;

use CensorCleaner\Helper;

/**
* @category CleanTalk
* @package CensorCleaner\Loader
* 
*/

Class CensMysql implements IntCens
{
    /**
    * Get Censor Words, returning format array('word' => 'replacement')
    * @return array
    * 
    */
    static function getCens(){
        return Helper::array_2d(
            \CensorCleaner\CleanTalk::$getter["mysql"]->get_data(array('query'=>"SELECT * FROM forum.phpbb_words", 'multi' => TRUE))
            ,'word','replacement');
    }
}