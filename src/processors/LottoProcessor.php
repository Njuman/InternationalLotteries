<?php

namespace InternationalLotteries\processors;

use SplFileObject;

interface LottoProcessor
{
    public function __construct(SplFileObject $entries, string $result, SplFileObject $output);

    public function call();

    public function getResult(): array;

    public function getTicket(): array;
}