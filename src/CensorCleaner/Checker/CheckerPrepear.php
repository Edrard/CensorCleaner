<?php

namespace CensorCleaner\Checker;

use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\Checker
* 
* Prepear Message statistic, Proxy Pattern
*/

Class CheckerPrepear implements IntCheckerPrepear
{
    /**
    * Cleaned message
    * 
    * @var string
    */
    private   $msg;
    /**
    * Uncleaned message
    * 
    * @var string
    */
    private   $msg_unclean;
    protected $length                   = null;
    protected $count_words              = null;
    protected $count_upper_case         = null;
    protected $count_symbols            = null;
    protected $count_numbers            = null;
    protected $count_per_latter         = null;
    protected $count_per_word           = null;
    protected $count_censor             = null;
    protected $count_exclude_censor     = null;
    protected $censor_exclude_word_list = null;
    protected $count_censor_in_latters  = null;
    protected $count_lines              = null;
    protected $extract_links            = null;
    protected $censor_word_list         = null;
    protected $count_only_latter        = null;

    function __construct($msg_clean,$msg){
        $this->msg = $msg_clean;
        $this->msg_unclean = $msg;
    }   
    /**
    * Counting Total number of latters in string
    * 
    * @param string $this->msg
    * @return integer
    */
    public function countLength(){ 
        if($this->length == null){
            $this->length = mb_strlen($this->msg);
            Clean::$log->info('countLength - '.$this->length);
        }        
        return $this->length;
    }
    /**
    * Count total number of Words, separated by "spaces"
    * if $per = FALSE, then returning total number of words
    * if $per = TURE, then returning each number of words
    * 
    * @param string $this->msg
    * @param boolean $per
    * @return integer
    */
    public function countWords($per = FALSE){
        if($this->count_words == null || $this->count_per_word == null){
            $this->count_words    = 0;
            $this->count_per_word = array();
            foreach(explode(' ',trim(preg_replace("#[[:punct:]]#", " ", $this->msg))) as $val){
                if(trim($val)){
                    if(!isset($this->count_per_word[$val])){
                        $this->count_per_word[$val] = 1;    
                    }else{
                        $this->count_per_word[$val]++;
                    }
                    $this->count_words++;
                }
            }
            Clean::$log->info('countWords - '.$this->count_words);
            Clean::$log->info('countPerWord - '.serialize($this->count_per_word));
        }
        return $per ? $this->count_per_word : $this->count_words;
    }
    /**
    * Count total CAPS
    * 
    * @param string $this->msg
    * @param string $upper_case_preg 
    * Example  A-ZА-ЯЁ
    * $config->config['langs']['upper_case_preg']
    * 
    * @return integer
    */
    public function countUpperCase($upper_case_preg){
        if($this->count_upper_case == null){
            $this->count_upper_case = mb_strlen(preg_replace('![^'.$upper_case_preg.']+!u', '', $this->msg),'utf-8');  // Caps count
            Clean::$log->info('countUpperCase - '.$this->count_upper_case);
        }
        return $this->count_upper_case;
    }
    /**
    * Counting Total number of Symbols excluding  
    * from lang pack
    * 
    * @param string $this->msg
    * @param string $only_symb_preg
    * Example  0-9A-Za-zА-Яа-яЁё
    * $config['config']['langs']['exclude_preg']
    * 
    * 
    * @return integer
    */
    public function countSymbols($only_symb_preg){
        if($this->count_symbols == null){
            $this->count_symbols = mb_strlen(preg_replace('/['.$only_symb_preg.']/u','',$this->msg));
            Clean::$log->info('countSymbols - '.$this->count_symbols);
        }
        return $this->count_symbols;
    }
    /**
    * Count numbers
    * 
    * @param string $this->msg
    */
    public function countNumbers(){
        if($this->count_numbers == null){
            $this->count_numbers = strlen(preg_replace("/\D/iu", "", $this->msg));
            Clean::$log->info('countNumbers - '.$this->count_numbers);    
        }
        return $this->count_numbers;
    }
    /**
    * Counting number of each latter
    * 
    * @param string $this->msg
    * @return array
    */
    public function countPerLatter(){
        if($this->count_per_latter == null){
            $l = mb_strlen($this->msg);
            $this->count_per_latter = array();
            for($i = 0; $i < $l; $i++) {
                $char = mb_substr($this->msg, $i, 1);
                if(!array_key_exists($char, $this->count_per_latter)){
                    $this->count_per_latter[$char] = 0;
                }
                $this->count_per_latter[$char]++;
            }
            Clean::$log->info('countPerLatter - '.serialize($this->count_per_latter));
        }
        return $this->count_per_latter;
    }
    /**
    * Counting number of each word
    * 
    * @param string $this->msg
    * @return array
    */
    public function countPerWord(){
        return $this->countWords($this->msg,TRUE);
    }
    /**
    * Counting exclude Censors
    * 
    * @param array $pregs
    * @return integer
    */
    public function countCensorExclude($pregs) {
        if($this->count_exclude_censor == null){
            $this->count_exclude_censor = 0;
            $msg = $this->msg; 
            foreach($pregs as $censor_word => $init){
                $msg = preg_replace('#'.$init.'#iu', '', $msg, -1, $countin);
                if($countin > 0){ 
                    $this->count_exclude_censor            += $countin;
                    $this->censor_exclude_word_list[] = $censor_word;
                }
            }
            Clean::$log->info('countCensorExclude - '.$this->count_exclude_censor);
        }
        return $this->count_exclude_censor;
    }
    /**
    * Which words was in message from Exclude Censor list
    * 
    * @param mixed $pregs
    * @return array
    */
    public function censoredExcludeWords($pregs){
        $this->countCensor($pregs);    
        return $this->censor_exclude_word_list; 
    }
    /**
    * Counting number of Censors words
    * 
    * @param array $pregs
    * @return integer
    */                                   
    public function countCensor($pregs){
        if($this->count_censor == null || $this->count_censor_in_latters == null){
            $this->count_censor = 0;
            $this->count_censor_in_latters = 0;
            $msg = $this->msg;
            foreach($pregs as $censor_word => $init){
                $msg = preg_replace('#'.$censor_word.'#iu', '', $msg, -1, $countin);
                $length = isset($init['length']) ? $init['length'] : mb_strlen($censor_word);
                if($countin > 0){ 
                    $this->count_censor_in_latters += $countin * $length;
                    $this->count_censor            += $countin;
                    $this->censor_word_list[]       = $init['origin'].' x '.$countin;
                }
            }
            Clean::$log->info('countCensor - '.$this->count_censor);
            Clean::$log->info('countCensor - '.$this->count_censor_in_latters);
        }
        return $this->count_censor;
    }
    /**
    * Which words was in message
    * 
    * @param mixed $pregs
    * @return array
    */
    public function censoredWords($pregs){
        $this->countCensor($pregs);    
        return $this->censor_word_list; 
    }
    /**
    * Counting number of Censor words in latter
    * 
    * @param array $pregs
    * @return integer
    */
    public function countCensorInLatters($pregs){
        $this->countCensor($pregs);    
        return $this->count_censor_in_latters;  
    }
    /**
    * Counting Total number of lines in string
    * 
    * @param string $this->msg
    * @return integer
    */
    public function countLines(){
        if($this->count_lines == null){
            $this->count_lines = count(explode("\n",$this->msg));
            if($this->count_lines == 0){
                Clean::$log->warning('Line count = 0, message was: '.$this->msg);
                $this->count_lines = 1; 
            }
            Clean::$log->info('countLines - '.$this->count_lines);
        }        
        return $this->count_lines;
    }  
    /**
    * COunting only Latters
    *     
    * @param mixed $preg
    * @return integer
    */
    public function countOnlyLatters($preg){
        if($this->count_only_latter == null){
            $this->count_only_latter = $this->countLength() - $this->countSymbols($preg);
        }
        return $this->count_only_latter;
    }
    /**
    * Extracting links
    * @return array - array of links
    * 
    */
    public function extractLinks(){
        if($this->extract_links == null){
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(([a-zA-Z0-9\-\._\?\&\%\/]*))?/";   
            preg_match_all($reg_exUrl, html_entity_decode($this->msg_unclean), $url_chek_mains);   
            $this->extract_links = $url_chek_mains[0]; 
        }
        return $this->extract_links;
    }
    /**
    * Resset object
    * 
    * 
    */
    public function unsetAll(){
        foreach($this as &$val){
            $val = null;
        }
    }
    /**
    * Set new Message and Resset all
    * 
    * @param string $msg
    */
    public function setMsgs($msg_clean,$msg){
        $this->msg = $msg_clean;
        $this->msg_unclean = $msg;
        $this->unsetAll();
    }
}