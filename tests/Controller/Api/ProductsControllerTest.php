<?php

namespace App\Tests\Controller\Api;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductsControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testPostProduct()
    {
        $data = [
            'name' => 'Test Product',
            'price' => 29.99,
        ];

        $this->client->request('POST', '/api/products/', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testGetProduct()
    {
        // Create a product first
        $data = [
            'name' => 'Test Product',
            'price' => 29.99,
        ];

        $this->client->request('POST', '/api/products/', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $responseContent = $this->client->getResponse()->getContent();
        $product = json_decode($responseContent, true);

        // Assume the product ID is 1 for this example
        $this->client->request('GET', '/api/products/' . $product["id"]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPutProduct()
    {
        // Create a product first
        $data = [
            'name' => 'Test Product',
            'price' => 29.99,
        ];

        $this->client->request('POST', '/api/products/', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $responseContent = $this->client->getResponse()->getContent();
        $product = json_decode($responseContent, true);

        // Update the product
        $updateData = [
            'id' => $product["id"],
            'name' => 'Updated Product',
            'price' => 39.99,
        ];

        $result = $this->client->request('PUT', '/api/products/', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($updateData));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testGetNonExistentProduct()
    {
        $this->client->request('GET', '/api/products/999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testPostInvalidProduct()
    {
        $data = [
            'name' => '',
            'price' => -10, // Invalid price
        ];

        $this->client->request('POST', '/api/products/', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}