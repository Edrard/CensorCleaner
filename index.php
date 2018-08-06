<?php
        header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$new = new CensorCleaner\CleanTalk(Logger::DEBUG);
//
//$new->presetConfig('[url=http://fff.fff:saa4afsaf]sfasgas[/url] <a href="http:/"> fffffsssss111</a> ппппппп РРffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffРРРР ОООООООООО шшшшш !!!(((((((((((((())))))))))))))','guest','wot-news.com',TRUE);

//$new->presetConfig('[url=http://test2.wot-news.com/CensorCleaner/src/output/classes/CensorCleaner.Checker.CheckerPrepear.html#method_countLength]https://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.php[/url]При замене по шаблону с использованием ссылок на подмаски может возникнуть ситуация, когда непосредственно за маской следует цифра (например, установка цифры сразу после совпавшей маски). https://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.php https://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.phphttps://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.php','guest','wot-news.com',TRUE);
//$new->presetConfig('Всем привет выблядь сykiзаебали уже выблядкиkонченные','guest','wot-news.com',TRUE);

//$new->presetConfig('Команда в которой я играл, была лучшей в своем роде баланса','guest','wot-news.com',TRUE);

//$new->presetConfig('Команда http://wot-news.com/stat.html в которой я играл, была лучшей в своем роде баланса, но затем залипать пошли в пере баланс, ебал я вас в рот пиздец, вот вы пидоpы xуй вам хохол ебучий','guest','test2.wot-news.com');

//$new->presetConfig('Команда                                             sdadsa','guest','test2.wot-news.com');

//$new->presetConfig('Команда 578BFl7Im2A.net в которой я играл, была лучшей в своем роде баланса','guest','wot-news.com',TRUE);

$new->presetConfig('ххууууйй надоели все','guest','wot-news.com',TRUE);


$new->pressetAllBans();
echo $new->checkMessage();

// echo CensorCleaner\GetUserIp::getIp();

//print_r($new::$config);

//$new = new CensorCleaner\GetContentCurl();

//echo $new->getUrl('http://goo.gl/5p5Yvd');

//var_dump($new);
//echo $new->filterMessage();