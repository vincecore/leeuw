<?php

namespace App\Repository;

use App\ElasticSearch\ElasticSearchProduct;
use JoliCode\Elastically\Factory;

class ProductListingRepository
{
    public function search(string $query): array
    {
        $factory = new Factory([
            Factory::CONFIG_MAPPINGS_DIRECTORY => __DIR__ . '/../../config/mappings',
            Factory::CONFIG_INDEX_CLASS_MAPPING => [
                'products' => ElasticSearchProduct::class,
            ],
        ]);

        $client = $factory->buildClient();
        $results = $client->getIndex('products')->search($query);

        $products = [];
        foreach ($results->getResults() as $result) {
            $products[] = $result->getData();
        }

        return $products;
    }
}
