<?php

namespace InternationalLotteries;

use InternationalLotteries\processors\LottoProcessor;
use SplFileObject;

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
            $prefix = strtolower($lotto::PREFIX);
            $filesCollection = getFilesByDateRange('uploads', $prefix, $this->start, $this->end);

            foreach ($filesCollection as $drawId => $files) {
                $output = $this->save($prefix, $drawId);
                $entries = $files[1];
                $result = splFileObjectToArray($files[0])[1];

                $entries->seek(1);

                $output->fputcsv(explode(';', $lotto::HEADER), ';');

                $processor = new $lotto($files[1], $result, $output);
                $processor->call();
            }
        }
    }

    public function save($prefix, $drawId): SplFileObject
    {
        $filename = "output/$prefix-$drawId.csv";
        $file = getFileByName($filename, 'a');
        $file->ftruncate(0);

        return $file;
    }
}