<?php

declare(strict_types=1);

namespace App\Database;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation;

#[Entity]
class ProductImage
{
    #[Column(type: 'primary')]
    private ?int $id = null;

    public function __construct(
        #[Relation\BelongsTo(target: Product::class, nullable: false)]
        public Product $product,
        #[Column(type: 'string')]
        public string $url,
    ) {
    }
}
