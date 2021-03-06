<?php
  
namespace CensorCleaner\Language;

/**
* @category CleanTalk
* @package CensorCleaner\AbstractLanguage
* 
*/

interface IntLanguage
{
    public function exclude();
    public function latterChange();
    public function excludePreg();
    public function upperCasePreg();
    public function excludeList($cens);
    public function excludeFilter();
}