<?php

function verifyApiKey($apiKey)
{
    if ($apiKey !== 'e8f1997c763') {
        header('HTTP/1.1 403 Forbidden');
        exit;
    }
}

function parsePutDeleteData()
{
    if ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
        parse_str(file_get_contents("php://input"), $_POST);
    }
}
