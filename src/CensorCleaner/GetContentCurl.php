<?php

namespace CensorCleaner;

/**
* @category CleanTalk
* @package CensorCleaner
* 
*/

Class GetContentCurl implements IntGetContent
{
    /**
    * Connection timeout seconds
    * 
    * @var num                
    */
    public $timeout = 5;
    /**
    * Curl timeout seconds
    * 
    * @var num                
    */
    public $curl_timeout = 12;  
    /**
    * Redirect counter
    * 
    * @var number
    */
    private $redirects = 0;
    /**
    * Max Redirects
    * 
    * @var num
    */
    public $max_redirects = 12;
    /**
    * Follow Location setting, 0 - Off, 1 - On
    * 
    * @var numeric
    */
    public $follow_location = 0;                      
    /**
    * Get URL moving by redirects, without  CURLOPT_FOLLOWLOCATION
    * 
    * @param string $url
    * @return string
    */

    function getUrl($url){
        $this->redirects = 0;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
        $data = $this->_curl_redirection($ch);
        $info_data = curl_getinfo($ch);          
        curl_close($ch);     
        return $data;
    }
    /**
    * Get URL
    * 
    * @param string $url
    * @return string
    */
    function getUrlNoRedirect($config){
        $this->redirects = 0;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->follow_location); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);     
        curl_close($ch);     
        return $data;
    }
    /**
    * Helping funtion to move throught Redirects
    * 
    * @param resource $ch
    * @param boolean $curlopt_header
    */
    private function _curl_redirection($ch, $curlopt_header = false) {
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);
        if($this->redirects >= $this->max_redirects){
            return $data;
        }
        if(curl_errno($ch) != 0){
            return '';
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 301 || $http_code == 302) {
            list($header) = explode("\r\n\r\n", $data, 2);

            $matches = array();
            preg_match("/(Location:|URI:|location:)[^(\n)]*/", $header, $matches);
            $url = trim(str_replace($matches[1], "", $matches[0]));

            $url_parsed = parse_url($url);
            if (isset($url_parsed)) {
                curl_setopt($ch, CURLOPT_URL, $url);
                $this->redirects++;
                return $this->_curl_redirection($ch, $curlopt_header);
            }
        }   
        if ($curlopt_header) {
            return $data;
        } else {
            list(, $body) = explode("\r\n\r\n", $data, 2);
            return $body;
        }
    } 
}