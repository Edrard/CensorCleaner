<?php
  
namespace CensorCleaner\Checker;

/**
* @category CleanTalk
* @package CensorCleaner
* 
*/

interface IntBanUser
{
    function checkUserStatus();
    function getUserStatus();
    function setUserStatus($time);
    function addCookieWarning($name);
}