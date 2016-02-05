<?php

namespace Savage\Http\Util;

class Utils
{
    public function hash($input) {
        return hash('sha256', $input);
    }

    public function verifyHash($knownHash, $givenHash) {
        return hash_verify($knownHash, $givenHash);
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}