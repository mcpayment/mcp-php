<?php

use Dotenv\Dotenv;

if (!function_exists('env')) {
    function env(string $value) {
        $dotenv = Dotenv::createImmutable(dirname(__FILE__, 2))->load();
        
        return $_ENV[$value];
    }
}