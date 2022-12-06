<?php

namespace App\Jobs;

use App\Database\Product;
use App\Database\ProductImage;
use App\Repository\ProductRepository;
use Cycle\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Spiral\Core\InvokerInterface;
use Spiral\Queue\JobHandler;
use Symfony\Component\HttpClient\HttpClient;

class GetAllProducts extends JobHandler
{
    private string $hash = '10ddc63b94cf5c83b7474746ae22bab24e83d503834a72942577672af7df4cb2';

    public function __construct(
        InvokerInterface $invoker,
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    ) {
        parent::__construct($invoker);
    }

    public function invoke(): void
    {
        $firstPage = $this->getPage(1);

        $this->logger->info('Start import', $firstPage['data']['categoryProductSearch']['pagination']);

        foreach (range(1, $firstPage['data']['categoryProductSearch']['pagination']['totalPages']) as $pageNumber) {
            $page = $this->getPage($pageNumber);

            foreach ($page['data']['categoryProductSearch']['products'] as $product) {
                $product = $this->saveProduct($product);
            }

            $this->entityManager->run();
        }
    }

    private function getPage(int $pageNumber): array
    {
        $this->logger->info('GetPage '.$pageNumber);

        $jsonPath = __DIR__ . '/pages/' . $pageNumber . '.json';

        if (file_exists($jsonPath)) {
            return json_decode(file_get_contents($jsonPath), true, 512, JSON_THROW_ON_ERROR);
        }

        $req = HttpClient::create()
            ->request(
                'GET',
                'https://api.delhaize.be?operationName=GetCategoryProductSearch&variables={"lang":"nl","searchQuery":"","category":"","pageNumber":' . $pageNumber . ',"pageSize":100,"filterFlag":true}&extensions={"persistedQuery":{"version":1,"sha256Hash":"' . $this->hash . '"}}',
                [

                ]
            );

        file_put_contents($jsonPath, $req->getContent());

        return $req->toArray();
    }

    private function saveProduct(array $productData): Product
    {
        // dump($productData);

        $code = $productData['code'];

        $product = $this->productRepository->findOne(['code' => $code]);

        if (!$product) {
            $product = new Product(
                $code,
                $productData['name'],
                $productData['manufacturerName'],
                $productData['manufacturerSubBrandName'],
                $productData['url'],

                $productData['price']['approximatePriceSymbol'],
                $productData['price']['currencySymbol'],
                $productData['price']['formattedValue'],
                $productData['price']['priceType'],
                $productData['price']['supplementaryPriceLabel1'],
                $productData['price']['supplementaryPriceLabel2'],
                $productData['price']['showStrikethroughPrice'],
                $productData['price']['discountedPriceFormatted'],
                $productData['price']['discountedUnitPriceFormatted'],
                $productData['price']['unit'],
                $productData['price']['unitPriceFormatted'],
                $productData['price']['unitCode'],
                $productData['price']['unitPrice'],
                $productData['price']['value'],
            );

            foreach ($productData['images'] as $image) {
                $productImage = new ProductImage(
                    $product,
                    $image['url'],
                );
                $product->addImage($productImage);
                $this->entityManager->persist($productImage);
            }

            $this->entityManager->persist($product);
        } else {
            // $product->update();
        }

        return $product;
    }
}
