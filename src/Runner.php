<?php

namespace InternationalLotteries;

use InternationalLotteries\processors\LottoProcessor;

class Runner
{
    /**
     * @var array
     */
    public array $lotteries;

    /**
     * @var string
     */
    public string $start;

    /**
     * @var string
     */
    public string $end;

    /**
     * @param array $lotteries
     * @param string $start
     * @param string $end
     */
    public function __construct(array $lotteries = [], string $start = 'monday this week', string $end = 'sunday this week')
    {
        $this->start = $start;
        $this->end = $end;
        $this->lotteries = $lotteries;
    }

    /**
     * @return void
     * @var $processor LottoProcessor
     */
    public function run(): void
    {
        foreach ($this->lotteries as $lotto) {
            $files = getFilesByDateRange('uploads', $lotto::PREFIX, $this->start, $this->end);

            $processor = new $lotto($files[1], splFileObjectToArray($files[0]));
            $processor->call();
        }
    }
}