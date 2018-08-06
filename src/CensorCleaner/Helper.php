<?php

namespace CensorCleaner;

Class Helper {
    /**
    * Merging 2 arrays without replacing keys
    * 
    * @param array $array1
    * @param array $array2
    */
    static function array_special_merge($array1,$array2)
    {
        if(!is_array($array1)){
            $array1 = array();
        }
        if(is_array($array2)){
            foreach($array2 as $key2 => $val2){
                if(!array_key_exists($key2,$array1)){
                    $array1[$key2] = $val2;
                }else{
                    $array1[] = $val2;
                }
            }
        }

        return $array1;

    } 
    /**
    * Resorting array by parametr
    * 
    * @param mixed $array
    * @param mixed $param
    */
    static function array_resort($array,$param){
        $new = array();
        if(is_array($array)){
            foreach($array as $val){
                $new[$val[$param]] = $val;
            }
        }
        return $new;    
    }
    /**
    * Create new array with $key as key and $param as param
    * $param can be Empty, and if $key - empty, then numbers from 0 will used
    * 
    * @param array $array
    * @param string $key  if $key - empty, then will use numeric keys
    * @param string $param
    */
    static function array_2d($array,$key,$param){
        $new = array();       
        if(is_array($array)){
            $i = 0;
            foreach($array as $val){
                if((isset($val[$key]) || !$key) && (isset($val[$param]) || !$param)){
                    $new[$key ? $val[$key] : $i] = $param ? $val[$param] : '';
                    $i++;
                }
            }
        }
        return $new;
    }
    /**
    * Writing file
    * 
    * @param mixed $path
    * @param mixed $data
    * @param mixed $mode
    */
    static function write_file($path, $data, $mode = 'wb')
    {
        if ( ! $fp = @fopen($path, $mode))
        {
            return FALSE;
        }

        flock($fp, LOCK_EX);
        fwrite($fp, $data);
        flock($fp, LOCK_UN);
        fclose($fp);

        return TRUE;
    }
    /**
    * Reading file
    * 
    * @param mixed $file
    */
    static function read_file($file)
    {
        if ( ! file_exists($file))
        {
            return FALSE;
        }

        if (function_exists('file_get_contents'))
        {
            return file_get_contents($file);
        }

        if ( ! $fp = @fopen($file, 'rb'))
        {
            return FALSE;
        }

        flock($fp, LOCK_SH);

        $data = '';
        if (filesize($file) > 0)
        {
            $data =& fread($fp, filesize($file));
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        return $data;
    }
    /**
    * Directory map
    * 
    * @param string $source_dir
    * @param int $directory_depth
    * @param boolean $hidden
    * @return array
    */
    static function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
    {
        if ($fp = @opendir($source_dir))
        {
            $filedata    = array();
            $new_depth    = $directory_depth - 1;
            $source_dir    = rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

            while (FALSE !== ($file = readdir($fp)))
            {
                // Remove '.', '..', and hidden files [optional]
                if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.'))
                {
                    continue;
                }

                if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
                {
                    $filedata[$file] = directory_map($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
                }
                else
                {
                    $filedata[] = $file;
                }
            }

            closedir($fp);
            return $filedata;
        }

        return FALSE;
    }
    /**
    * Spliting utf8 string on parts
    * 
    * @param string $str
    * @param integer $len
    */
    static function utf8Split($str, $len = 1)
    {
        $arr = array();
        $strLen = mb_strlen($str, 'UTF-8');
        for ($i = 0; $i < $strLen; $i++)
        {
            $arr[] = mb_substr($str, $i, $len, 'UTF-8');
        }
        return $arr;
    }
    /**
    * Multibyte substr_replace function
    * 
    * @param string $string - String
    * @param string $replacement - Replacement
    * @param integer $start - start position
    * @param integer|null $length - length to replace
    * @param string|null $encoding - type of string encoding
    */
    static function mb_substr_replace($string, $replacement, $start, $length=null, $encoding=null) {
        if ($encoding == null) $encoding = mb_internal_encoding();
        if ($length == null) {
            return mb_substr($string, 0, $start, $encoding) . $replacement;
        }
        else {
            if($length < 0) $length = mb_strlen($string, $encoding) - $start + $length;
            return 
            mb_substr($string, 0, $start, $encoding) .
            $replacement .
            mb_substr($string, $start + $length, mb_strlen($string, $encoding), $encoding);
        }
    }
} 