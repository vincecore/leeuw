<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Product;
use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\Select;
use Cycle\ORM\Select\Repository;

class ProductRepository extends Repository
{
    public function __construct(Select $select, private EntityManagerInterface $entityManager)
    {
        parent::__construct($select);
    }

    public function add(Product $product): void
    {
        $this->entityManager->persist($product);
    }
}
