<?php

namespace CensorCleaner\Checker;

/**
* @category CleanTalk
* @package CensorCleaner\Checker
* 
*/

Interface IntCheckerPrepear
{
    public function countLength();
    public function countWords($per);
    public function censoredWords($pregs);
    public function countUpperCase($upper_case_preg);
    public function countSymbols($only_symb_preg);
    public function countNumbers();
    public function countPerLatter();
    public function countPerWord();    
    public function countCensor($pregs);
    public function countCensorExclude($pregs);
    public function countCensorInLatters($pregs);
    public function countLines();
    public function extractLinks();
    public function countOnlyLatters($pregs);
    public function setMsgs($msg_clean,$msg);
    public function unsetAll();
}