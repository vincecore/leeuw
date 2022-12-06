<?php

declare(strict_types=1);

namespace App\Database;

use App\Repository\ProductRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation;
use Cycle\Annotated\Annotation\Table\Index;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[Entity(repository: ProductRepository::class)]
#[Index(['code'], true)]
class Product
{
    #[Column(type: 'primary')]
    private ?int $id = null;
    /**
     * @var Collection|ProductImage[]
     * @psalm-var Collection<int, ProductImage>
     */
    #[Relation\HasMany(target: ProductImage::class)]
    private Collection $images;

    public function __construct(
        #[Column(type: 'string')]
        public string $code,
        #[Column(type: 'string')]
        public string $name,
        #[Column(type: 'string', nullable: true)]
        public ?string $manufacturerName,
        #[Column(type: 'string', nullable: true)]
        public ?string $manufacturerSubBrandName,
        #[Column(type: 'string')]
        public string $url,

        #[Column(type: 'string')]
        public ?string $priceApproximatePriceSymbol,
        #[Column(type: 'string')]
        public string $priceCurrencySymbol,
        #[Column(type: 'string')]
        public string $priceFormattedValue,
        #[Column(type: 'string')]
        public string $priceType,
        #[Column(type: 'string')]
        public string $priceSupplementaryPriceLabel1,
        #[Column(type: 'string')]
        public string $priceSupplementaryPriceLabel2,
        #[Column(type: 'boolean')]
        public bool $priceShowStrikethroughPrice,
        #[Column(type: 'string')]
        public string $priceDiscountedPriceFormatted,
        #[Column(type: 'string')]
        public string $priceDiscountedUnitPriceFormatted,
        #[Column(type: 'string')]
        public string $priceUnit,
        #[Column(type: 'string')]
        public string $priceUnitPriceFormatted,
        #[Column(type: 'string')]
        public string $priceUnitCode,
        #[Column(type: 'string')]
        public string $priceUnitPrice,
        #[Column(type: 'string')]
        public string $priceValue,
    ) {
        $this->images = new ArrayCollection();
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function toElastic(): array
    {
        $data = get_object_vars($this);
        $data['images'] = [];

        foreach ($this->images as $image) {
            $data['images'][] = $image->url;
        }

        unset($data['__cycle_orm_rel_map']);
        unset($data['__cycle_orm_relation_props']);
        unset($data['__cycle_orm_rel_data']);

        return $data;
    }

    public function addImage(ProductImage $image): void
    {
        $this->images->add($image);
    }
}
