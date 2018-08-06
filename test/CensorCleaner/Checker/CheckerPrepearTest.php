<?php

namespace CensorCleaner\Checker;

use \CensorCleaner\CleanTalk  as Clean;

/**
* @category CleanTalk
* @package CensorCleaner\Checker
* 
* Prepear Message statistic, Proxy Pattern
*/

Class CheckerPrepearTest extends \PHPUnit_Framework_TestCase
{
    public function testCountLength(){ 

        $clean = $this->getMock('\CensorCleaner\CleanTalk::$log');
        $clean::staticExpects($this->any())
        ->method('baz')
        ->with($this->equalTo('somevar value'));

        $new = new \CensorCleaner\Checker\CheckerPrepear('asdfgАПРОД  ()124б,', 'asdfgАПРОД  ()124б,');
        $this->assertEquals(19,$new->countLength());
    }
}