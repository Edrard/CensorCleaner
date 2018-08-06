<?php

namespace CensorCleaner;

use \CensorCleaner\CleanTalk  as Clean;

/**
* Pre Clean Message Class
* 
* @category CleanTalk
* @package CensorCleaner\CleanTalk
*/

Class PreCleanMessage implements IntPreCleanMessage  
{
    /**
    * Cleaned message
    * 
    * @var string
    */
    private $msg_clean = '';
    /**
    * Excluder class what prepearing RegExps
    * 
    * @var Loader\IntListOfExclude
    */
    private $excluder;
    /**
    * Array of handeling class what returning array of removing lists, to presset look at CleanTalk.json
    * 
    * @var array
    */
    private $remove_bbcodes_classes;
    /**
    * Strip tags variable
    * 
    * @var integer 0|1, by default 1 and tags will be striped
    */
    public $strip_tags;
    function __construct($msg, Loader\IntListOfExclude $excluder, array $remove_bbcodes_classes = array(),$strip_tags = 1){
        $this->msg_clean = $msg;
        $this->strip_tags = $strip_tags;
        $this->excluder = $excluder;
        $this->remove_bbcodes_classes = $remove_bbcodes_classes;
    }
    /**
    * Clening message method
    * 
    * @return string - cleaned message
    * 
    */
    public function cleanMessage(){
        $this->replaceTagsToNewLine();
        $this->lowerCase();
        $this->strip_tags == 1 ? $this->stripTags() : '';    
        $this->removingBBCodesa();
        $this->replaceHttp();
        Clean::$log->debug('Cleaned Message: '.$this->msg_clean);
        return $this->msg_clean;
    } 
    /**
    * Adding to end of tags new line
    * 
    */
    private function replaceTagsToNewLine(){
        $this->msg_clean = preg_replace("/<br.*>/iU", "$0\n", $this->msg_clean);
        $this->msg_clean = preg_replace("/<\/div.*>/iU", "$0\n", $this->msg_clean);
        $this->msg_clean = preg_replace("/<\/p.*>/iU", "$0\n", $this->msg_clean);   
    }
    /**
    * Replace http://
    * 
    */
    private function replaceHttp(){
        $this->msg_clean = preg_replace('#\b(https?|ftp|file):\/\/#i', '', $this->msg_clean); 
    }
    /**
    * Lowering case
    * 
    */
    private function lowerCase(){
        $this->msg_clean = strtolower($this->msg_clean);  
    }
    /**
    * Striping Tags
    * 
    */
    private function stripTags(){
        $this->msg_clean = strip_tags($this->msg_clean);
    }
    /**
    * Excluding BBCodes
    * 
    */
    private function removingBBCodesa(){
        if(!$exclude = Clean::$cache->getCache('exclude_list')){  
            /** Setting Classes to get exclude lists **/
            foreach(array_keys($this->remove_bbcodes_classes) as $ex_class ){
                $class = __NAMESPACE__.$ex_class;
                $this->excluder->setHendlers(new $class());
            }
            $exclude = $this->excluder->setupExcludeList();
            Clean::$cache->saveCache('exclude_list',$exclude,7200);
        }  
        foreach($exclude as $val){
            $this->msg_clean = preg_replace($val,'',$this->msg_clean);        
        }
    }
}