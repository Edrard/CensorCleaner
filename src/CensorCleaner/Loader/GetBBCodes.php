<?php

namespace CensorCleaner\Loader;

use CensorCleaner\Helper;

/**
* @category CleanTalk
* @package CensorCleaner\Loader
* 
*/

Class GetBBCodes implements IntGetSingleList
{  
    function getList(){
        return Helper::array_2d(
            \CensorCleaner\CleanTalk::$getter["mysql"]->get_data(array('query'=>"SELECT bbcode_tag FROM forum.phpbb_bbcodes", 'multi' => TRUE))
            ,'','bbcode_tag');

    }

}