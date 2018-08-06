<?php

namespace CensorCleaner;
use \CensorCleaner\Language as Lang;
use \CensorCleaner\CleanTalk  as Clean;
use \CensorCleaner\Loader;

/**
* Singleton Config
* 
* @category CleanTalk
* @package CensorCleaner\CleanTalk
*/

Class Config 
{
    /**
    * Checking message
    * 
    * @var mixed
    */
    public $msg;
    /**
    * On what we change rude words
    * 
    * @var mixed
    */
    public $cens = '';
    /**
    * Private config
    * 
    * @var array
    */
    public $config = array();
    private static $cache_time = 21600;
    private static $instance;  
    public static function getInstance() {   
        if ( empty(self::$instance) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
    * Setup Language pack
    * 
    * @param mixed $langs
    */
    public function setupLangs($lang){
        $this->config['langs'] = array();
        $class = __NAMESPACE__.'\\Language\\'.$lang;
        $obj_lang = new $class();
        if($obj_lang instanceof Lang\IntLanguage){
            $this->config['langs']['latter_change'] = $obj_lang->latterChange();
            $this->config['langs']['exclude_preg'] = $obj_lang->excludePreg();
            $this->config['langs']['upper_case_preg'] = $obj_lang->upperCasePreg();
            $this->config['langs']['exclude_list'] = $obj_lang->excludeList($this->cens);
            $this->config['langs']['exclude_filter'] = $obj_lang->excludeFilter();
        }else{
            throw new BaseException('Wrong Language package Class');
        }
        unset($obj_lang,$class);
    } 
    /**
    * Presseting ban list look at CLeanTalk.json config
    * 
    * @param array $ban_class - Ban Classes array
    * @param string $array_key - type name
    * @param array $bans - external list
    */
    public function presetBans($ban_class, $array_key, array $bans = array()){
        if(empty($bans)){  
            Clean::$log->debug($array_key.' Class - '.$ban_class);
            /**Cache reading**/
            if(!$bans = Clean::$cache->getCache($array_key)){  
                $class = __NAMESPACE__.'\\Loader\\'.$ban_class; 
                /**Geting Cens if no cache**/
                $bans = $class::getBan();   
                Clean::$cache->saveCache($array_key,$bans,self::$cache_time);
            }
            if(empty($bans)){
                throw new BaseException('Empty '.$array_key);
            }          
        }else{
            Clean::$log->debug('External '.$array_key.' loaded');
        }
        $this->config[$array_key] = $bans;
    }
    /**
    * Setup Censor list
    * 
    * @param string $cens_class - Class to read Censor List with IntCens Interface
    * @param array $cens_exteral_list - External Censor List
    */
    public function setupList($cens_class,array $cens_exteral_list = array()){  
        $cens_list['swap_list'] = array();
        $cens_list['cens_list'] = array();
        if(empty($cens_exteral_list)){  
            Clean::$log->debug('Cens List Class - '.$cens_class);
            /**Cache reading**/
            if(!$cens_list = Clean::$cache->getCache('cens_list')){  
                $class = __NAMESPACE__.'\\Loader\\'.$cens_class; 
                /**Geting Cens if no cache and sor it**/
                foreach($class::getCens() as $word => $repl){
                    if($this->cens == $repl){
                        $cens_list['cens_list'][$word] = $repl;    
                    }else{
                        $cens_list['swap_list'][$word] = $repl;
                    }
                }
                $cens_list['cens_list'] = $this->extendReplaceCens($cens_list['cens_list']);
                Clean::$cache->saveCache('cens_list',$cens_list,self::$cache_time);
            }
            if(empty($cens_list)){
                throw new BaseException('Empty Censor List');
            }          
        }else{
            foreach($cens_exteral_list as $word => $repl){
                if($this->cens == $repl){
                    $cens_list['cens_list'][$word] = $repl;    
                }else{
                    $cens_list['swap_list'][$word] = $repl;
                }
            }
            $cens_list['cens_list'] = $this->extendReplaceCens($cens_list['cens_list']);
            Clean::$log->debug('External Cens List loaded');
        }
        $this->config['replace'] = $cens_list;
    }
    /**
    * Transforming Censor words to RegExp's
    * 
    * @param array $cens_list - censor words list, key - word, val - replacement
    */
    private function extendReplaceCens($cens_list){
        //$this->config['langs']['latter_change']
        foreach($cens_list as $text => $repl){
            $arr = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
            foreach($arr as $kk => $ll){
                $arr[$kk] = (isset($this->config['langs']['latter_change'][$ll]) ? $this->config['langs']['latter_change'][$ll] : $ll).'+';
            }
            $word = implode('[^'.$this->config['langs']['exclude_preg'].']*',$arr);
            $cens_list[$word]['change'] = $repl;
            $cens_list[$word]['length'] = mb_strlen($text);
            $cens_list[$word]['origin'] = $text;
            unset($cens_list[$text]);
        }
        return $cens_list; 
    }
}