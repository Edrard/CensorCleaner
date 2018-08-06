<?php

namespace CensorCleaner\Loader;

use CensorCleaner\Helper;

/**
* @category CleanTalk
* @package CensorCleaner\Loader
* 
*/

Class GetBaseBBCodes implements IntGetSingleList
{  
    function getList(){

        return array_keys(array(
            'b' => '',
            'i' => '',
            'u' => '',
            'quote' => '',
            'code' => '',
            'list' => '',
            '*' => '',
            'img' => '',
            'url' => '',
            'flash' => '',
            'color' => '',
            'youtube' => '',
            'size' => ''    
        ));
    }

}