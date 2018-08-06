<?php

namespace CensorCleaner\Loader;


/**
* @category CleanTalk
* @package CensorCleaner\Checker
*
* Additional level of abstraction to read Temporary ban File 
*/

Class FileTmpBanUsers implements IntTmpBanWorker
{
    public $worker;
    public $file_name;
    function __construct(IntFileWorker $worker,$file_name = 'tmp_ban.json'){
        $this->worker = $worker;   
        $this->file_name = $file_name;
    }
    function readTmpBanUsers(){
        return $this->worker->readFile($this->file_name);           
    }
    function writeTmpBanUsers(array $data){
        return $this->worker->writeFile($data,$this->file_name);
    }
}