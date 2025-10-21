<?php

namespace App\Service;

use App\DTO\ProductConverter;
use App\DTO\ProductDTO;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Exception;
use Psr\Log\LoggerInterface;

class ProductService {

    public function __construct(
        private SkuGeneratorInterface $skuGenerator, 
        private EntityManagerInterface $em, 
        private LoggerInterface $logger) { }

    public function create(ProductDTO $productDto): ProductDTO {
        try {
            // Create Product From DTO and Save it in DB
            $product = ProductConverter::toEntity($productDto, null);
            $product->setSku($this->skuGenerator->generate($productDto->name));

            $this->em->persist($product);
            $this->em->flush();
            
            // Set ProductDTO Data
            $productDto = ProductConverter::toDto($product);
        } catch (Exception $e) {
            $this->logger->error("[Service : ProductService - Method : create] : " . $e->getMessage());
        }

        return $productDto;
    }

    public function search($id): ProductDTO|null {
        $productDto = null;

        try {
            // Search Product By ID
            $product = $this->em->getRepository(Product::class)->find($id);

            // If product exist, convert it to DTO
            if (!empty($product)) {
                $productDto = ProductConverter::toDto($product);
            }
        } catch (Exception $e) {
            $this->logger->error("[Service : ProductService - Method : search] : " . $e->getMessage());
        }

        return $productDto;
    }

    public function update(ProductDTO $productDto): ProductDTO|null {
        try {
            // Search Product
            $product = $this->em->getRepository(Product::class)->find($productDto->id);

            // If product exists, update it
            if (!empty($product)) {
                $product = ProductConverter::toEntity($productDto, $product);
                $product->setSku($this->skuGenerator->generate($productDto->name));
                $product->setUpdatedAt(new DateTime());

                // Save Product in DB
                $this->em->persist($product);
                $this->em->flush();

                // Convert Product To DTO
                $productDto = ProductConverter::toDto($product);
            }
        } catch (Exception $e) {
            $this->logger->error("[Service : ProductService - Method : update] : " . $e->getMessage());
        }

        return $productDto;
    }
}