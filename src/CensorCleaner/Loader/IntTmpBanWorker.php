<?php
  
namespace CensorCleaner\Loader;

/**
* @category CleanTalk
* @package CensorCleaner
* 
*/

interface IntTmpBanWorker
{
    function readTmpBanUsers();
    function writeTmpBanUsers(array $ban_list);
}