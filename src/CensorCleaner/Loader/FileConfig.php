<?php

namespace CensorCleaner\Loader;


/**
* @category CleanTalk
* @package CensorCleaner\Checker
*
* Additional level of abstraction to read Config File 
*/

Class FileConfig implements IntConfigWorker
{
    public $worker;
    function __construct(IntFileWorker $worker){
        $this->worker = $worker;   
    }
    function readConfig($file_name){
        return $this->worker->readFile($file_name);           
    }
    function readAllConfigs(){
        return $this->worker->readAllFiles();
    }
    function writeConfig(array $config,$file_name){
        return $this->worker->writeFile($config,$file_name);
    }
}