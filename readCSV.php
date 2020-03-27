<?php

function readRawLines($fileName) {
    $dataHandle = fopen($fileName, 'r');
    $dataRaw = fread($dataHandle, filesize($fileName));
    fclose($dataHandle);

    return explode("\n", $dataRaw);
}

function readHeader($rawLines) {
    return rawLineToCSV($rawLines[0]);
}

function readDataRows($rawLines) {
    array_shift($rawLines); // First line contains headers
    array_shift($rawLines); // Second line is empty
    array_pop($rawLines); // LF at the end of the file makes explode return empty line at the end
    return array_map(function ($line) {
        return rawLineToCSV($line);
    }, $rawLines);
}

function rawLineToCSV($rawLine) {
    $line = iconv('windows-1250', 'utf-8', $rawLine);
    return str_getcsv($line);
}

function getVoivodeshipTree($rows) {
    $country = array();
    foreach ($rows as $row) {
        $voivodeshipName = getSchoolVoivodeship($row);
        $countyName = getSchoolCounty($row);
        $parishName = getSchoolParish($row);
        $cityName = getSchoolCity($row);

        $voivodeship = $country[$voivodeshipName];
        if($voivodeship == null) {
            $voivodeship = array();
            $country[$voivodeshipName] = $voivodeship;
        }

        $county = $voivodeship[$countyName];
        if($county == null) {
            $county = array();
            $voivodeship[$countyName] = $county;
        }

        $parish = $county[$parishName];
        if($parish == null) {
            $parish = array();
            $county[$parishName] = $parish;
        }

        if(!in_array($cityName, $parish)) array_push($parish, $cityName);

        $county[$parishName] = $parish;
        $voivodeship[$countyName] = $county;
        $country[$voivodeshipName] = $voivodeship;
    }
    return $country;
}

function getSchoolTypes($rows) {
    $schoolTypes = array();
    foreach ($rows as $row) {
        $schoolType = getSchoolType($row);
        if(!in_array($schoolType, $schoolTypes)) array_push($schoolTypes, $schoolType);
    }
    return $schoolTypes;
}

function findSchoolWithId($rows, $id) {
    foreach ($rows as $row) {
        if(getSchoolId($row) == $id) return $row;
    }
    return null;
}

function getSchoolId($row) {
    return $row[0];
}

function getSchoolName($row) {
    return $row[14];
}

function getSchoolType($row) {
    return $row[12];
}

function getSchoolVoivodeship($row) {
    return $row[5];
}

function getSchoolCounty($row) {
    return $row[6];
}

function getSchoolParish($row) {
    return $row[7];
}

function getSchoolCity($row) {
    return $row[9];
}

function getSchoolStreet($row) {
    return $row[16];
}

function getSchoolHouseNumber($row) {
    return $row[17];
}

$rawLines = readRawLines('dane.csv');
$header = readHeader($rawLines);
$rows = readDataRows($rawLines);

$voivodeshipTree = getVoivodeshipTree($rows);
$schoolTypes = getSchoolTypes($rows);
