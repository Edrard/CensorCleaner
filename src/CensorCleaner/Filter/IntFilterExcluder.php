<?php
  
namespace CensorCleaner\Filter;

/**
* @category CleanTalk
* @package CensorCleaner
* 
*/

interface IntFilterExcluder
{
    public function checkExcludes($filters);
    public function getSwapsMatches();
    public function countSwapsMatches();
    
}