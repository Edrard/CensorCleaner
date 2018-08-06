<?php

namespace CensorCleaner\Cache;

/**
* @category CleanTalk
* @package CensorCleaner\Cache
* 
*/

interface IntCache
{
    /** 
    * Save Cacahe
    * 
    * @param string $id
    * @param mixed $data
    * @param numeric $ttl
    * @return boolean
    */
    public function saveCache($id, $data, $ttl);
    /**
    * Get Cahce
    * 
    * @param string $id
    * @return mixed
    */
    public function getCache($id);
    /**
    * Delete Cache
    * 
    * @param string $id
    * @return boolean
    */
    public function deleteCache($id);
}