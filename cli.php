<?php

require 'vendor/autoload.php';

use InternationalLotteries\processors\GermanyLottoProcessor;
use InternationalLotteries\processors\ItalyLottoProcessor;
use InternationalLotteries\processors\NorwayLottoProcessor;
use InternationalLotteries\Runner;

$continue = commandLineInputBuffer("Welcome to international lotto processing tool?  Type 'yes' to continue: ");

if ($continue != 'yes' && $continue != 'y') {
    exit();
}

$start = commandLineInputBuffer(
    "Please select start-date you would like processing result from, e.g 03-11-2019 or leave blank: "
);

if ($start != null) {
    $end = commandLineInputBuffer(
        "Please select end-date you would like processing stop processing results from, e.g 08-11-2019 or leave blank: "
    );
} else {
    $start = null;
    $end = null;
}

echo "Processing all lotto results from $start to $end";

$runner = new Runner([
    GermanyLottoProcessor::class,
    ItalyLottoProcessor::class,
    NorwayLottoProcessor::class,
], '03-11-2019', '07-11-2019');

$runner->run();

echo "\n";
echo "Thank you, continuing...\n";
