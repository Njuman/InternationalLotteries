<?php

namespace InternationalLotteries\comparators;

trait StandardComparator
{
    public function compare($comparison1, $comparison2, $result): array
    {
        $comparisonResult1 = array_filter($comparison1, function ($v) use ($result) {
            return in_array($v, $result[0]);
        });

        $comparisonResult2 = array_filter($comparison2, function ($v, $k) use ($result) {
            return $v == $result[1][$k];
        }, ARRAY_FILTER_USE_BOTH);

        return [$comparisonResult1, $comparisonResult2];
    }
}