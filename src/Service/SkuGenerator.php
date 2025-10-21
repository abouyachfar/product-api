<?php

namespace App\Service;

class SkuGenerator implements SkuGeneratorInterface{

    public function generate(string $productName): string {
        return "PROD-" . strtoupper(substr($productName, 0, 4)) . "-" . $this->randomString(7);
    }

    public function randomString(int $length): string
    {
        $str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str), 0, $length); 
    }

}