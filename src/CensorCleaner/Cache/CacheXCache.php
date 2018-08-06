<?php

namespace CensorCleaner\Cache;

use CensorCleaner\Helper;

/**
* @category CleanTalk
* @package CensorCleaner\Cache
* 
*/
Class CacheXCache implements IntCache
{
    function __construct(){ 
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
        
        return xcache_set($id, $data, $ttl);     
         
    }
    /**
    * Get Cahce
    * 
    * @param string $id
    * @return mixed
    */
    public function getCache($id){
        
        return xcache_get($id);
        
    }
    /**
    * Delete Cache
    * 
    * @param string $id
    * @return boolean
    */
    public function deleteCache($id){
        
        return xcache_unset($id);
        
    }
}