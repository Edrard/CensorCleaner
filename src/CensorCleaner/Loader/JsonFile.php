<?php

namespace CensorCleaner\Loader;

use CensorCleaner\Helper;

/**
* @category CleanTalk
* @package CensorCleaner\Checker
* 
*/

Class JsonFile implements IntFileWorker
{
    var $_path;

    function __construct($path = ''){
        if($path){
            if(DIRECTORY_SEPARATOR == '\\'){
                if(!preg_match("#\/$#iu", $path)){
                    $path .= '\\';
                }
            }else{
                if(!preg_match("#\\$#iu", $path)){
                    $path .= '/';
                }
            }
        }
        if (!file_exists($path)) {
            throw('Cant find folder: '.$path);    
        }
        $this->_path = $path;    
    }
    function readFile($file_name){
        if ( ! file_exists($this->_path.$file_name))
        {
            return FALSE;
        } 
        $data = Helper::read_file($this->_path.$file_name);
        $data = json_decode($data,TRUE);
        return !empty($data) ? $data : FALSE;           
    }
    function readAllFiles(){
        if($files = Helper::directory_map($this->_path)){
            if(!empty($files)){
                foreach($files as $file_name){
                    $config[$file_name] = $this->readConfig($file_name);    
                }
                return $config;
            }
        }    
        return FALSE;
    }
    function writeFile(array $config,$file_name){
        if (Helper::write_file($this->_path.$file_name, json_encode($config)))
        {
            @chmod($this->_path.$file_name, 0777);
            return TRUE;            
        }
    }
}