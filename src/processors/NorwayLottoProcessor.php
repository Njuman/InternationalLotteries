<?php

namespace InternationalLotteries\processors;

use InternationalLotteries\comparators\StandardComparator;
use SplFileObject;

class NorwayLottoProcessor implements LottoProcessor
{
    use StandardComparator;

    public const PREFIX = 'Norway';

    public const HEADER = 'ticket_id;mainballs;sub1;sub2;matched_main_ball_set;matched_main_ball_set;jackpot';

    private const NUMBER_REQUIRED_FOR_JACKPOT = 7;

    /**
     * @var SplFileObject
     */
    private SplFileObject $entries;

    /**
     * @var string
     */
    private string $result;

    /**
     * @var SplFileObject
     */
    private SplFileObject $output;

    /**
     * @param SplFileObject $entries
     * @param string $result
     * @param SplFileObject $output
     */
    public function __construct(SplFileObject $entries, string $result, SplFileObject $output)
    {
        $this->entries = $entries;
        $this->result = $result;
        $this->output = $output;
    }

    /**
     * @return void
     */
    public function call()
    {
        $result = $this->getResult();

        while (!$this->entries->eof()) {
            $ticket = $this->getTicket();
            $comparison = $this->compare($ticket[1], $ticket[2], $result);

            $fields = $this->fields($ticket, $comparison);
            $this->output->fputcsv($fields, ";");
        }
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        $columns = explode(';', $this->result);
        return [explode(':', $columns[0]), [$columns[1], $columns[2]]];
    }

    /**
     * @return array
     */
    public function getTicket(): array
    {
        $columns = $this->entries->fgetcsv(';', "\r", "\r");
        return [$columns[0], explode(':', $columns[1]), [$columns[2], trim(preg_replace('/\s\s+/', ' ', $columns[3]))]];
    }

    /**
     * @param $ticket
     * @param $comparison
     * @return array
     */
    private function fields($ticket, $comparison): array
    {
        $matchNumbers = count($comparison[0]);
        $matchAdditionalNumbers = count($comparison[1]);
        $jackpot = ($matchNumbers + $matchAdditionalNumbers) == self::NUMBER_REQUIRED_FOR_JACKPOT ? 1 : 0;

        return [$ticket[0], join(":", $ticket[1]), $ticket[2][0], $ticket[2][1], $matchNumbers, $matchAdditionalNumbers, $jackpot];
    }
}