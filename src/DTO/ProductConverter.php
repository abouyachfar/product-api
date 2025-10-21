<?php

namespace App\DTO;

use App\Entity\Product;

class ProductConverter {
    static function toEntity(ProductDto $ProductDto, ?Product $product): Product {
        if ($product == null) {
            $product = new Product();
        }
        
        $product->setName($ProductDto->name);
        $product->setPrice($ProductDto->price);

        return $product;
    }

    static function toDto(Product $product): ProductDTO {
        $productDto = new ProductDTO();

        $productDto->id = $product->getId();
        $productDto->name = $product->getName();
        $productDto->price = $product->getPrice();
        $productDto->sku = $product->getSku();
        $productDto->createdAt = $product->getCreatedAt()->format("d/m/Y H:i:s");
        $productDto->updatedAt = !empty($product->getUpdatedAt()) ? $product->getUpdatedAt()->format("d/m/Y H:i:s") : "";

        return $productDto;
    }
}