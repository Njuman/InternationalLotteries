<?php

function getFileByName(string $filename, $mode = 'r'): SplFileObject
{
    return new SplFileObject($filename, $mode);
}

function getFilesByDateRange($directory, $prefix, $start, $end, $mode = 'r'): array
{
    $filenames = glob("$directory/$prefix*.csv");
    $pattern = "/(\d{2}-\d{2}-\d{4})_(\d+)/";
    $files = [];
    $matches = [];

    foreach ($filenames as $filename)
    {
        preg_match($pattern, $filename, $matches);
        $timestamp = strtotime($matches[1]);
        $drawId = $matches[2];

        if ($timestamp >= strtotime($start) && $timestamp <= strtotime($end)) {
            $files[$drawId][] = getFileByName($filename, $mode);
        }
    }

    return $files;
}

function splFileObjectToArray(SplFileObject $file): array
{
    $result = [];

    foreach ($file as $row) {
        $result[] = $row;
    }

    return $result;
}

function commandLineInputBuffer(string $prompt = null): string
{
    echo $prompt;
    $handle = fopen ("php://stdin","r");
    $output = fgets ($handle);
    return trim ($output);
}