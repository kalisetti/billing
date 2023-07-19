<?php

namespace App\Utils;

function parseRequestBody()
{
    return json_decode(file_get_contents('php://input'), true);
}

function scrub($input)
{
    return str_replace([' ', '-'], '_', strtolower($input));
}

function makeAutoName($db, $series) {
    $rows = $db->sql("SELECT * FROM series WHERE name = '$series'");
    $currentValue = 0;
    if (!empty($rows)) {
        $currentValue = $rows[0]['current'] + 1;
        $db->sql("UPDATE series SET current = $currentValue WHERE name = '$series'");
    } else {
        $db->sql("INSERT INTO series(name, current) VALUES('$series', 0)");
    }
    return $series . str_pad($currentValue,5,"0",STR_PAD_LEFT);
}