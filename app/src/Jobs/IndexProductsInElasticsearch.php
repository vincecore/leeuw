<?php

namespace App\Jobs;

use App\Database\Product;
use App\ElasticSearch\ElasticSearchProduct;
use Cycle\ORM\Iterator;
use Cycle\ORM\ORM;
use Elastica\Document;
use JoliCode\Elastically\Factory;
use Spiral\Core\InvokerInterface;
use Spiral\Queue\JobHandler;
use Storms\OmgevingsloketTracker\Infrastructure\ElasticSearch\ElasticSearchProject;

class IndexProductsInElasticsearch extends JobHandler
{
    public function __construct(
        InvokerInterface $invoker,
        private ORM $orm,
    ) {
        parent::__construct($invoker);
    }

    public function invoke(): void
    {
        $factory = new Factory([
            Factory::CONFIG_MAPPINGS_DIRECTORY => __DIR__ . '/../../config/mappings',
            Factory::CONFIG_INDEX_CLASS_MAPPING => [
                'products' => ElasticSearchProduct::class,
            ],
        ]);

        $client = $factory->buildClient();

        $indexName = 'products';
        $indexBuilder = $client->getIndexBuilder();
        $newIndex = $indexBuilder->createIndex($indexName);
        $indexer = $client->getIndexer();

        foreach ($this->getProducts() as $product) {
            $indexer->scheduleIndex($newIndex, new Document($product->getId(), $product->toElastic()));
        }

        $indexer->flush();

        $indexBuilder->markAsLive($newIndex, $indexName);
        $indexBuilder->speedUpRefresh($newIndex);
        $indexBuilder->purgeOldIndices($indexName);
    }

    /**
     * @return Product[]|Iterator
     */
    private function getProducts(): Iterator
    {
        $select = $this->orm->getRepository(Product::class)->select()
            ->load('images');

        return $select->getIterator();
    }
}
