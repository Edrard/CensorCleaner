<?php

namespace CensorCleaner\Filter;

/**
* @category CleanTalk
* @package CensorCleaner\Filter
* 
*/

Class Filter
{

    /**
    * CleanTalk config
    * 
    * @var \CensorCleaner\Config
    */
    private $config;
    /**
    * User weight
    * 
    * @var mixed
    */
    private $weight;
    /**
    * Filtering classes from Filter.json
    * 
    * @var IntBanUser
    */
    private $filter_classes;

    /**
    * Prepearing checker
    * 
    * @param \CensorCleaner\Config $config
    * @param IntCheckerPrepear $check_prepear
    * @param IntCookieBan $cookie_ban
    * @param IntBanUser $ban_user
    * @param IntGetContent $url_get
    */
    public function getPrepea(\CensorCleaner\Config $config, IntFilterExcluder $excluder, array $user_config, array $filter_classes){
        $this->config         = $config;
        $this->weight         = $user_config[$this->config->config['user_lvl']]['wieght'];
        $this->filter_classes = $filter_classes;
        $this->excluder       = $excluder;
        //$this->config->config['user_lvl'];  -- User lvl
        // $this->user_config[$this->config->config['user_lvl']]['wieght'];  - wheight
    }
    /**
    * Adding Filtering classes and running them
    * 
    */
    public function run(){
        $handler = new \CensorCleaner\Filter\Filtering();
        $return_message = $this->config->msg;
        foreach($this->filter_classes as $class_name => $min){
            $ch_class = '\\CensorCleaner\\Filter\\'.$class_name;
            $handler->addHandler(new $ch_class($this->config,$this->excluder,$min));
        }
        return $handler->filter($this->weight,$return_message);
    }
}