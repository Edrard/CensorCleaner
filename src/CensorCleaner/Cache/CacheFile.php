<?php

namespace CensorCleaner\Cache;

use CensorCleaner\Helper;

/**
* @category CleanTalk
* @package CensorCleaner\Cache
* 
*/
Class CacheFile implements IntCache
{
    var $_cache_path;

    function __construct($path){
        if($path){
            if(DIRECTORY_SEPARATOR == '\\'){
                if(!preg_match("#\/$#iu", $path)){
                    $path .= '\\';
                }
            }else{
                if(!preg_match("#\\$#iu", $path)){
                    $path .= '/';
                }
            }
        }
        $this->_cache_path = $path;    
    }
    /**
    * Save Cacahe
    * 
    * @param string $id
    * @param mixed $data
    * @param numeric $ttl
    * @return boolean
    */
    public function saveCache($id, $data, $ttl = 60){
        $contents = array(
            'time'        => time(),
            'ttl'        => $ttl,            
            'data'        => $data
        );

        if (Helper::write_file($this->_cache_path.$id, serialize($contents)))
        {
            @chmod($this->_cache_path.$id, 0777);
            return TRUE;            
        }

        return FALSE;        
    }
    /**
    * Get Cahce
    * 
    * @param string $id
    * @return mixed
    */
    public function getCache($id){

        if ( ! file_exists($this->_cache_path.$id))
        {
            return FALSE;
        }                      
        $data = Helper::read_file($this->_cache_path.$id);
        $data = unserialize($data);
        //print_r($data);die;
        if (time() >  $data['time'] + $data['ttl'])
        {
            unlink($this->_cache_path.$id);
            return FALSE;
        }
        return $data['data'];

    }
    /**
    * Delete Cache
    * 
    * @param string $id
    * @return boolean
    */
    public function deleteCache($id){
        return unlink($this->_cache_path.$id);
    }
}