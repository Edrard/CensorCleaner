<?php

namespace CensorCleaner\Filter;

use \CensorCleaner\CleanTalk  as Clean;
use \CensorCleaner\Helper;

/**
* @category CleanTalk
* @package CensorCleaner\Checker
* 
* Prepear Message statistic, Proxy Pattern
*/

Class FilterExcluder implements IntFilterExcluder
{
    /**
    * Unclean message
    * 
    * @var string
    */
    public   $msg;
    /**
    * Array of filtering ReGExp's
    * 
    * @var array
    */
    public   $pregs;
    /**
    * Special, rare used marker
    * 
    * @var string
    */
    private  $sw = '♠';
    /**
    * Ammount of swaps per RegExp 
    * 
    * @var array
    */
    protected  $count_swaps       = null;
    /**
    * Array of matches from $pregs
    * 
    * @var mixed
    */
    protected  $matches           = null;
    /**
    * Compares exceptions and filter
    * 
    * @var array
    */
    protected  $exclude_matches   = null ;  
    /**
    * Count total swaps maked by RegExps
    * 
    * @var integer
    */
    protected  $count_total_swaps = null;
    /**
    * Posisitions of excludes in Message
    * 
    * @var array
    */
    protected  $position          = null;

    function __construct($msg, array $pregs){
        $this->msg = $msg;
        $this->pregs = $pregs;
    }  
    /**
    * Compares exceptions and filter, making associative array
    *  
    * @param array $filters - array of RegExp in key
    * @return array
    */
    public function checkExcludes($filters){
        if($this->exclude_matches == null){
            foreach(array_keys($this->pregs) as $word){
                foreach(array_keys($filters) as $filter){
                    if(preg_match('#'.$filter.'#iu',$word)){
                        $this->exclude_matches[$filter][] = $word;
                    }
                }
            }
        }
        return $this->exclude_matches;
    }
    /**
    * Gettting matches in Message from $preg list
    * 
    * @return array
    */
    public function getSwapsMatches(){
        if($this->matches == null){
            foreach($this->pregs as $key => $change){
                preg_match_all("#".$change."#iu",$this->msg,$matches[$key]);    
            }
            $this->matches = $matches; 
        }   
        return $this->matches;      
    }
    /** 
    * Ammount of swaps per RegExp 
    * 
    * @return array
    */
    public function countSwapsMatches(){
        if($this->count_swaps == null){
            $this->count_total_swaps = 0;
            $count = $this->getSwapsMatches();
            foreach($count as $word => $in){
                $this->count_swaps[$word] = count($in[0]);
                $this->count_total_swaps += $this->count_swaps[$word]; 
            }
        }
        return $this->count_swaps;
    }
    /**
    * Total swaps
    * 
    * @return integer
    */
    public function countTotalSwaps(){
        if($this->count_total_swaps == null){
            $this->countSwapsMatches();    
        }
        return $this->count_total_swaps;
    }
    /** 
    * Getting position of maked swaps
    * 
    * @return array
    */
    public function getPositions(){
        if($this->position == null){
            foreach($this->pregs as $key => $change){
                $msg = preg_replace("#".$change."#iu",$this->sw,$this->msg,-1,$count);    
                if($count > 0){
                    $offset = 0;
                    while($pos = mb_strpos(' '.$msg, $this->sw,$offset)){
                        $tmp[$key][] = $pos - 1; 
                        $msg = preg_replace('#'.$this->sw.'#iu', $key, $msg, 1);
                        $offset = $pos +1;  
                    }
                }
            }
            $this->position = array();
            if(is_array($tmp)){
                foreach($tmp as $key => $val){
                    $this->position[$key] = $val;
                }
            }
        }
        return $this->position; 
    }
}