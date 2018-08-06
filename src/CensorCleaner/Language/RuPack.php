<?php

namespace CensorCleaner\Language;

/**
* @category CleanTalk
* @package CensorCleaner\AbstractLanguage
* @method array latterChange()
* @method string excludePreg()
*/

Class RuPack implements IntLanguage
{
    private $sym = '+|+';

    /**
    * Excluding list, use $this->sym liek a separator
    * @return array
    * 
    */
    public function exclude(){
        return array('ебаланс' => '#'.$this->sym.'анс#ui',
            'е баланс'     => '#'.$this->sym.'анс#ui',
            'ребаланс'     => '#р'.$this->sym.'анс#ui',   
            'команда'      => '#ко'.$this->sym.'#ui',
            'залипать'     => '#зал'.$this->sym.'#ui',
            'употреблять'  => '#употре'.$this->sym.'#ui',
            'каждым разом' => '#кажды'.$this->sym.'ом#ui',
            'м разные'     => '#м'.$this->sym.'разные#ui',
            'высоким разовым' => '#высоки'.$this->sym.'овым#ui',
            'jmY2_2z9ShY\[\/youtube' => '#jmY2_2z9S'.$this->sym.'outube#ui',
            'нему, да кантузия' =>  '#не'.$this->sym.'антузия#ui',
            'причем разбег' =>  '#приче'.$this->sym.'бег#ui',
            'попробуем разобраться' => '#попробуе'.$this->sym.'обраться#ui',
            'своем развитии' => '#свое'.$this->sym.'витии#ui'
        );
    }
    /**
    * Language latter alternatives
    * @return array
    */
    public function latterChange()
    {
        return array( 
            'о' => '[0oо]', 
            'х' => '[хxh]',
            'й' => '[йy]',
            'у' => '[yуu]',
            'л' => '((Jl)|l|л)', 
            'р' => '[pр]', 
            'а' => '[aа@]', 
            'е' => '[eеё]',
            'п' => '[nп]',
            'и' => '[иi!uN]',
            'д' => '[dд]',
            'с' => '[сsc$]',
            'ы' => '((!Ь)|ы|(Ы)|(bi))',
            'з' => '[зz3]',
            'б' => '[bб]',
            'к' => '[kк]',
            'm' => '[mм]',
            'd' => '[dд]',
            'ч' => '[4ч]',
        );
    }
    /**
    * Regexp language + roman latters and numbers
    * @return string 
    */
    public function excludePreg(){
        return '0-9A-Za-zА-Яа-яЁё';
    }
    /** 
    * Upper case preg
    * @return string 
    */
    public function upperCasePreg(){
        return 'A-ZА-ЯЁ';
    }
    /**
    * Excluding list
    * 
    * @param mixed $cens
    * @return array
    */
    public function excludeList($cens){
        $cens = preg_quote($cens, '/');
        $new = array();
        foreach($this->exclude() as $text => $val){
            $new[$text] = preg_replace("/\+\|\+/iu", $cens, $val);
        }
        return $new; 
    }
    public function excludeFilter(){
        $change = '[^'.$this->excludePreg().']+';
        $new = array();
        foreach($this->exclude() as $text => $val){
            $new[$text] = preg_replace("/\s/iu", $change, $text);
        }
        return $new;  
    }
    /**
    * Changing separator
    * 
    * @param string $sym
    */
    public function changeSeparator($sym){
        $this->sym = $sym;
    }
}