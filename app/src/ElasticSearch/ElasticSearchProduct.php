<?php

namespace App\ElasticSearch;

use Doctrine\Common\Collections\ArrayCollection;

class ElasticSearchProduct
{
    public function __construct(
        public string $code,
        public string $name,
        public ?string $manufacturerName,
        public ?string $manufacturerSubBrandName,
        public string $url,
        public ?string $priceApproximatePriceSymbol,
        public string $priceCurrencySymbol,
        public string $priceFormattedValue,
        public string $priceType,
        public string $priceSupplementaryPriceLabel1,
        public string $priceSupplementaryPriceLabel2,
        public bool $priceShowStrikethroughPrice,
        public string $priceDiscountedPriceFormatted,
        public string $priceDiscountedUnitPriceFormatted,
        public string $priceUnit,
        public string $priceUnitPriceFormatted,
        public string $priceUnitCode,
        public string $priceUnitPrice,
        public string $priceValue,
    ) {
    }
}
