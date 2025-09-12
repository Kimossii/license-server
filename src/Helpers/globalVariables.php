<?php

use App\Services\LicenseService;
use App\Models\License;
use Illuminate\Support\Facades\File;



if (!function_exists('getClientStoragePath')) {

    function getServerStorageKeys()
    {
        return storage_path('keys');
    }

    function getFilePrivateKey()
    {
        return getServerStorageKeys() . '/private.pem';
    }

}

if (!function_exists('write_license_log')) {
    function write_license_log(string $mensagem): void
    {
        \Log::info('[License] ' . $mensagem); // usa o log do Laravel
    }
}

/*
if (!function_exists('write_license_log')) {
    function write_license_log(string $message): void
    {
        $logDir = storage_path('logs');
        $logPath = $logDir . '/license.log';
        $timestamp = now()->toDateTimeString();

        if (!File::exists($logDir)) {
            File::makeDirectory($logDir, 0755, true);
        }

        $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;

        // cria ou adiciona no arquivo
        File::append($logPath, $logMessage);
    }
}*/
