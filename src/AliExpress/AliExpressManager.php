<?php


namespace App\AliExpress;


class AliExpressManager
{

    private $provider;

    private $dataSaver;

    private $productId;

    private $product;

    private $productDescription;

    public function __construct(AliExpressProvider $provider, AliExpressDataSaver $dataSaver)
    {
        $this->provider = $provider;
        $this->dataSaver = $dataSaver;
    }

    public function addProduct(array $product)
    {
        $this->setAliExpressProductId($product);

        $this->product = $this->provider->getProductData($this->productId);

        $this->productDescription = $this->provider->getProductDescripion($this->productId);

        $this->dataSaver->storeProduct($this->product, $this->productDescription);

        $this->dataSaver->storeImages($this->product);
    }

    public function  setAliExpressProductId(array $product)
    {
        preg_match('@/(\d+)\.html@',$product['importLink'], $id);
        $this->productId = $id[1];
    }
}