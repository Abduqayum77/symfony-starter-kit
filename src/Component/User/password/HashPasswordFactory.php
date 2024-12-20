<?php

namespace App\Component\User\password;

use App\Entity\Password;
use App\Entity\User;

class HashPasswordFactory
{
    private string $ciphering = "AES-128-CTR";
    private string $encryption_iv = 'TulqinM123456789';

    private string $padded_iv;

    private int $options = 0;

    public function __construct()
    {
        $this->padded_iv = str_pad($this->encryption_iv, 16, "\0");
    }

    public function encrypt($password, $encryption_key = 'center'): false|string
    {
        return openssl_encrypt($password, $this->ciphering, $encryption_key, $this->options, $this->padded_iv);
    }

    public function decrypt($password, $encryption_key = 'center'): false|string
    {
        return openssl_decrypt($password, $this->ciphering, $encryption_key, $this->options, $this->padded_iv);
    }

    public function create(User $user, $password): Password
    {
        return (new Password())->setUser($user)->setPassword($password);
    }

}
