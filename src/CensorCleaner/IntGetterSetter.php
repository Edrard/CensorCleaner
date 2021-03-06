<?php
  
namespace CensorCleaner;

/**
* @category CleanTalk
* @package CensorCleaner
* 
*/

interface IntGetterSetter
{
    function get_data($config);
    function change_data($config);
    function insert_data($config);
    function delete_data($config);
}