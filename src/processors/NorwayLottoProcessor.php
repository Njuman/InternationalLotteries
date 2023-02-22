<?php

namespace InternationalLotteries\processors;

use SplFileObject;

class NorwayLottoProcessor implements LottoProcessor
{
    const PREFIX = 'norway';

    private SplFileObject $entries;

    private array $result;

    public function __construct($entries, $result)
    {
        $this->entries = $entries;
        $this->result = $result;
    }

    public function call()
    {
        while (!$this->entries->eof()) {
            print $this->entries->fgetcsv();
        }
    }

    public function getResult(): array
    {
        return [];
    }

    public function getTicket(): array
    {
        return [];
    }
}