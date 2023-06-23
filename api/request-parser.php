<?php

function parseRequestBody()
{
    $request_body = file_get_contents('php://input');
    return json_decode($request_body, true);
}

?>
