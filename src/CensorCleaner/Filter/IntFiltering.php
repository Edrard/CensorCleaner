<?php
  
namespace CensorCleaner\Filter;

/**
* @category CleanTalk
* @package CensorCleaner
* 
*/

interface IntFiltering
{
    public function filter($lvl,$message);
}