<?php

namespace InternationalLotteries\processors;

use InternationalLotteries\comparators\StandardComparator;
use SplFileObject;

class GermanyLottoProcessor implements LottoProcessor
{
    use StandardComparator;

    const PREFIX = 'germany';

    const NUMBER_REQUIRED_FOR_JACKPOT = 7;

    private SplFileObject $entries;

    private array $result;

    public function __construct($entries, $result)
    {
        $this->entries = $entries;
        $this->result = $result;
    }

    public function call()
    {
        $result = $this->getResult();

        while (!$this->entries->eof()) {
            $ticket = $this->getTicket();
            $comparison = $this->compare($ticket[1], $ticket[2], $result);
            $ticketOutCount = $this->ticketOutcome($ticket, $comparison);
            $this->saveToCsv($ticketOutCount);
        }
    }

    public function getResult(): array
    {
        $columns = $this->result[0][0];
        return [explode(':', $columns[0]), [$columns[1]]];
    }

    public function getTicket(): array
    {
        $columns = $this->entries->fgetcsv(';');
        return [$columns[0], explode(':', $columns[1]), [$columns[2]]];
    }

    private function ticketOutcome($ticket, $comparison): array
    {
        $matchNumbers = count($comparison[0]);
        $matchAdditionalNumbers = count($comparison[1]);
        $jackpot = ($matchNumbers + $matchAdditionalNumbers) == self::NUMBER_REQUIRED_FOR_JACKPOT ? 1 : 0;

        return [$ticket[0], join(":", $ticket[1]), $matchNumbers, $matchAdditionalNumbers, $jackpot];
    }

    public function saveToCsv($ticketOutcome) {
        $fields = [
            $ticketOutcome[0],
            $ticketOutcome[1],
            $ticketOutcome[2],
            $ticketOutcome[3],
            $ticketOutcome[4]
        ];

        $csv = fopen('outcome.csv', 'a');
        fputcsv($csv, $fields, ";");
        fclose($csv);
    }
}