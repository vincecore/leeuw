<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductListingRepository;
use Spiral\Prototype\Traits\PrototypeTrait;

class ProductController
{
    use PrototypeTrait;

    public function __construct(private ProductListingRepository $productListingRepository)
    {
    }

    public function listing(): array
    {
        return [
            'status' => 200,
            'data' => $this->productListingRepository->search(
                $this->input->query->get('query')
            ),
        ];
    }
}
