<?php

namespace App\DTO;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO
{
    public string $id;
    
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type("float")]
    #[Assert\Positive()]
    public float $price;

    public string $sku;
    
    public string $createdAt;
    
    public ?string $updatedAt;

    public function __construct()
    {
    }
}