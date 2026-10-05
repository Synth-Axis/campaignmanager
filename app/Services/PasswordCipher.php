<?php

class PasswordCipher
{
    // Preserve the existing key and format so saved accesses remain readable.
    private const KEY = 'chave-super-secreta';

    public function encrypt(string $password): ?string
    {
        if ($password === '') return null;
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($password, 'aes-256-cbc', self::KEY, 0, $iv);
        if ($encrypted === false) throw new RuntimeException('Não foi possível encriptar a senha.');
        return base64_encode($iv . $encrypted);
    }

    public function decrypt(?string $stored): string
    {
        if (!$stored) return '';
        $data = base64_decode($stored, true);
        if ($data === false || strlen($data) <= 16) return '';
        $password = openssl_decrypt(substr($data, 16), 'aes-256-cbc', self::KEY, 0, substr($data, 0, 16));
        return $password === false ? '' : $password;
    }
}
