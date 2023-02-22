<?php

namespace InternationalLotteries\processors;

class ItalyLottoProcessor implements LottoProcessor
{
    const PREFIX = 'italy';

    public function call()
    {
        print "Yebo YES Italy";
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