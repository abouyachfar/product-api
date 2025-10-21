<?php

namespace App\Service;

interface SkuGeneratorInterface {

    public function generate(string $productName): string;
    
}