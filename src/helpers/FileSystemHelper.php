<?php

function getFileByName(string $filename, $mode = 'r'): SplFileObject
{
    return new SplFileObject($filename, $mode);
}

function getFilesByDateRange($directory, $prefix, $start, $end, $mode = 'r'): array
{
    $filenames = glob("$directory/$prefix*.csv");
    $pattern = "/(?<day>\d{2})-(?<month>\d{2})-(?<year>\d{4})/";
    $files = [];
    $matches = [];

    foreach ($filenames as $filename)
    {
        preg_match($pattern, $filename, $matches);
        $timestamp = strtotime($matches[0]);

        if ($timestamp >= strtotime($start) && $timestamp <= strtotime($end)) {
            $files[$timestamp][] = getFileByName($filename, $mode);
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