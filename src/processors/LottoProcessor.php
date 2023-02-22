<?php

namespace InternationalLotteries\processors;

interface LottoProcessor
{
    public function call();

    public function getResult(): array;

    public function getTicket(): array;
}