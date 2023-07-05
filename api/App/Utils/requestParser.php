<?php

namespace App\Utils;
function parseRequestBody()
{
    return json_decode(file_get_contents('php://input'), true);
}
