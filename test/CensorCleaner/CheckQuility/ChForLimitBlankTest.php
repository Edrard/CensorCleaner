<?php
namespace CensorCleaner\CheckQuility;

use CensorCleaner\Checker;
use \CensorCleaner\CleanTalk  as Clean;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ChForLimitBlankTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @runInSeparateProcess
    */
    public function testCheckQuility()
    {
        $new = new Clean(Logger::DEBUG);

        $new->presetConfig('Команда                                             sdadsa','guest','test2.wot-news.com');
        $new->pressetAllBans();
        $this->assertEquals(\CensorCleaner\Checker\Checker::BANED_RETARD_BLANK, $new->checkMessage());
    }
}
