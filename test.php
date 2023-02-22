<?php

require 'vendor/autoload.php';

use InternationalLotteries\ThreadManager;
//use InternationalLotteries\processors\GermanyLottoProcessor;
//use InternationalLotteries\Runner;
//
//$runner = new Runner([
//    GermanyLottoProcessor::class,
////    ItalyLottoProcessor::class,
////    NorwayLottoProcessor::class,
//], '03-11-2019', '04-11-2019');
//
//$runner->run();

$files = getFilesByDateRange('uploads', 'germany', '02-11-2019', '04-11-2019');

foreach ($files as $file) {
    $result = splFileObjectToArray($file[0]);
    $entries = $file[1];

    $threadManager = new ThreadManager($entries,2);

    $callback = function (SplFileObject $chunk) use ($result) {
        print_r($chunk);
    };

    $threadManager->run($callback);

    $x = 's';
}

