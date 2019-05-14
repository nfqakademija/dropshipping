<?php


namespace App\AliExpress;


use AliseeksApi\Api\ProductsApi;
use AliseeksApi\ApiException;
use AliseeksApi\Configuration;
use AliseeksApi\Model\ProductDetailsRequest;
use AliseeksApi\Model\ProductHtmlDescriptionRequest;
use GuzzleHttp\Client;

class AliExpressProvider
{
    private $apiAccessKey = 'TEIEQFRZKOYVNPUL';

    private $product;

    private $product_description;

    private $config;

    private $apiInstance;

    private $productRequest;

    private $productDescriptionRequest;

    public function __construct(ProductDetailsRequest $productRequest, ProductHtmlDescriptionRequest $productDescriptionRequest)
    {
        $this->config = Configuration::getDefaultConfiguration()->setApiKey('X-API-CLIENT-ID', $this->apiAccessKey);
        $this->apiInstance = new ProductsApi(new Client(), $this->config);
        $this->productRequest = $productRequest;
        $this->productDescriptionRequest = $productDescriptionRequest;
    }

    public function getProductData($productId)
    {
        $this->productRequest->setProductId($productId);

        try {
            $this->product = $this->apiInstance->getProductDetails($this->productRequest);
        } catch (ApiException $e) {
            echo 'Exception when calling ProductsApi->getProduct: ', $e->getMessage(), PHP_EOL;
        }
        return $this->product;
    }

    public function getProductDescripion($productId)
    {
        $this->productDescriptionRequest->setProductId($productId);

        try {
            $this->product_description = $this->apiInstance->getProductHtmlDescription($this->productDescriptionRequest);
        } catch (ApiException $e) {
            echo 'Exception when calling ProductsApi->getProduct: ', $e->getMessage(), PHP_EOL;
        }
        return $this->product_description;
    }
}