<?php


namespace App\Amazon;


class AmazonManager{
    
    private $provider;

    private $dataSaver;

    private $productId;

    private $product;

    public function __construct(AmazonProvider $provider, AmazonDataSaver $dataSaver)
    {
        $this->provider = $provider;
        $this->dataSaver = $dataSaver;
    }

    public function addProduct(array $product)
    {
        $this->setProductId($product);

        $this->product = $this->provider->getProductData($this->productId);

        $this->dataSaver->storeProduct($this->product);

    }

    public function  setProductId(array $product)
    {
        $product;
        $this->productId = "B07JHJ56PR";
    }
    
}