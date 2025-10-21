<?php

namespace App\Tests\Service;

use App\Service\SkuGenerator;
use PHPUnit\Framework\TestCase;

class SkuGeneratorTest extends TestCase
{
    public function testGenerate()
    {
        $skuGenerator = new SkuGenerator();

        // Test with a product name
        $productName = "TestProduct";
        $sku = $skuGenerator->generate($productName);

        // Check if the SKU starts with 'PROD-'
        $this->assertStringStartsWith('PROD-', $sku);

        // Check that the second part of the SKU matches the first 4 characters of the product name
        $this->assertEquals(substr($sku, 5, 4), strtoupper(substr($productName, 0, 4)));
    }

    public function testRandomString()
    {
        $skuGenerator = new SkuGenerator();

        // Test the length of the random string
        $randomString = $skuGenerator->randomString(7);
        $this->assertEquals(7, strlen($randomString));

        // Check that the string contains only valid characters
        $this->assertMatchesRegularExpression('/^[0-9A-Za-z]+$/', $randomString);
    }
}