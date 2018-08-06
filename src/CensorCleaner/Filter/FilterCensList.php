<?php

namespace CensorCleaner\Filter;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;
use \CensorCleaner\Helper;

/**
* @category CleanTalk
* @package CensorCleaner\Filter
* 
*/


class FilterCensList implements IntFiltering
{
    /**
    * Min weight to run this class
    * 
    * @var mixed
    */
    public $weight = '1';
    /**
    * CleanTalk config
    * 
    * @var \CensorCleaner\Config
    */
    public $config;
    /**
    * Filters Countings
    * 
    * @var IntFilterExcluder
    */
    private $excluder;
    /** 
    * Unique UTF-8 symbol
    * 
    * @var mixed
    */
    private $sw = '♠';
    function __construct(\CensorCleaner\Config $config, IntFilterExcluder $excluder, $weight){
        $this->weight        = $weight;
        $this->config        = $config;
        $this->excluder      = $excluder;
    }
    /**
    * Filter method
    * 
    * @param integet $usr_level - user level
    * @param string $msg - message
    * 
    * @return string - retrun filtred message
    */
    // TODO: Slice method
    public function filter($usr_level,$msg)
    {
        //    $this->config->config['replace']['cens_list']
        //    $this->config->config['langs']['exclude_preg']
        $msg_base = $msg;
        $swaped = $this->excluder->getPositions();
        $exclude = $this->excluder->checkExcludes($this->config->config['replace']['cens_list']);
        $count_swaps = $this->excluder->countSwapsMatches();
        $count_swaps_total = $this->excluder->countTotalSwaps();
        //print_r($swaped);
        //print_r($exclude); 
        if ($usr_level <= $this->weight) {
            foreach($this->config->config['replace']['cens_list'] as $cword => $init){
                //$init['change']
                $new = preg_replace('#'.$cword.'#iu', $this->sw, $msg_base, -1, $count);
                if($count > 0 && isset($exclude[$cword]) && $count_swaps_total > 0){
                    preg_match_all("#".$cword."#iu",$msg_base,$matches);
                    $offset = 0;
                    $i = 0;
                    while($pos = mb_strpos(' '.$new, $this->sw,$offset)){
                        $goin[$cword][$i] = $pos - 1; 
                        $new = preg_replace('#'.$this->sw.'#iu', $matches[0][$i], $new, 1);
                        $offset = $pos +1;
                        $i++;  
                    } 
                    $bad_positions = array();
                    foreach($goin[$cword] as $position){
                        $fire = FALSE;
                        foreach($exclude[$cword] as $in_exclude){
                            if(isset($swaped[$in_exclude])){
                                $in_exclude_len = mb_strlen($in_exclude);
                                foreach($swaped[$in_exclude] as $swap_position){
                                    if($position >= $swap_position && $position <= $swap_position + $in_exclude_len ){
                                        $fire = FALSE;
                                        break;
                                    }else{
                                        $fire = TRUE;  
                                    }    
                                }
                            }
                        }  
                        if($fire !== FALSE){
                            $bad_positions[] = $position;
                        }
                    }
                    if( !empty($bad_positions)){
                        foreach($bad_positions as $bpos){
                            $key_bpos = array_search($bpos,$goin[$cword]);
                            $msg = Helper::mb_substr_replace($msg, $init['change'], $bpos, mb_strlen($matches[0][$key_bpos]));    
                        }
                    }
                }else{
                    $msg = preg_replace('#'.$cword.'#iu', $init['change'], $msg, -1, $count);
                }
            }
        }
        return $msg;
    }
}  