<?php
  
namespace CensorCleaner\Loader;

/**
* @category CleanTalk
* @package CensorCleaner
* 
*/

interface IntFileWorker
{
    function readFile($file_name);
    function readAllFiles();
    function writeFile(array $config,$file_name);
}