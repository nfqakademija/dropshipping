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

        $item=$this->dataSaver->storeProduct($this->product);
        
        $this->dataSaver->storeImages($this->product, $item);

    }

    public function  setProductId(array $product)
    {
        //var_dump($product["importLink"]);
        $explodedLink= explode("/", $product["importLink"]);
        $loopNum=0;
        $productLocalId="";
        foreach ($explodedLink as $key=>$value){
            if($value==="dp"){
                $productLocalId=$explodedLink[$loopNum+1];
            }
            $loopNum=$loopNum+1;
            
        }
        
        if($productLocalId===""){
            throw new \Exception("Cant set productLocalId. Maybe invalid Amazon Product link");
        }
        
        //exit();

        $this->productId = $productLocalId;
    }
    
}