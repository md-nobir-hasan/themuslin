<?php

namespace App\Http\Services;

class EncryptionService
{
    public static function encrypt($data): string
    {
        $cipher = 'AES-256-CBC';
        $options = 0;
        $iv = openssl_random_pseudo_bytes(16);
        $key = config('app.encryption.key');

        return base64_encode(openssl_encrypt($data, $cipher, $key, $options, $iv));
    }

    public static function decrypt($encryptedData): bool|string
    {
        $cipher = 'AES-256-CBC';
        $options = 0;
        $iv = openssl_random_pseudo_bytes(16);
        $key = config('app.encryption.key');

        return openssl_decrypt(base64_decode($encryptedData), $cipher, $key, $options, $iv);
    }
}
