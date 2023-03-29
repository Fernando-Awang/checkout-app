<?php

function printError($th)
{
    return (object)[
        'message' => $th->getMessage(),
        'file' => $th->getFile(),
        'line' => $th->getline(),
    ];
}

