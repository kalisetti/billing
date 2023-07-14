<?php

namespace App\Utils;
function scrub($input)
{
    return str_replace([' ', '-'], '_', strtolower($input));
}
