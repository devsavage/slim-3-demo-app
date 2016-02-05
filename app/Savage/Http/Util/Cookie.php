<?php

namespace Savage\Http\Util;

class Cookie
{
    public static function exists($name){
        return (isset($_COOKIE[$name])) ? true : false;
    }

    public static function get($name) {
        return $_COOKIE[$name];
    }

    public static function set($name, $value, $expiry, $secure = false) {
        if(setcookie($name, $value, $expiry, '/', null, $secure, true)) {
            return true;
        }
        
        return false;
    }

    public static function delete($name) {
        self::set($name, '', time() - 1);
    }
}