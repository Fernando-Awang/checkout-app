<?php

function printError($th)
{
    return (object)[
        'message' => $th->getMessage(),
        'file' => $th->getFile(),
        'line' => $th->getline(),
    ];
}

function userId()
{
    return auth()->user()->id;
}

function sendResponseJson($success, $msg='Terjadi Kesalahan, Buka Log Error', $data = null, $code=200)
{
    return response()->json([
        'success' => $success,
        'message' => $msg,
        'data' => $data,
    ], $code);
}

function formatRupiah($val)
{
    return 'Rp. ' . number_format($val, 0, ',', '.');
}

function generateRandomString($length)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
