<?php


namespace App\Amazon;


class AmazonProvider{
    
    //private $productData;
    
    
    public function __construct()
    {
        
    }
    
    public function getProductData($productId)
    {
        //return $this->productData;
        $productData=$this->getProduct($productId);
        return $productData;
    }

    /**
     * @throws \Exception
     */
    private function getProduct($asin) {
        //echo "Getting product with asin#$asin...";
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://bf5f9.l.dedikuoti.lt/?mode=api&key="
                ."ksdsdffrfr85444555Ghhdjh.fjdfhksdsdffrfr85444555Ghhdjh.fjdfhksdsdffrfr85444555Ghhdjh.fjdfhksdsdffrfr85444555Ghhdjh.fjdfhlklklklksdsdffrfr85444555Ghhdjh.fjhgf".
                "&asin=".$asin);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                "Authorization: token"
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

            $responseDec=json_decode($response, true);
        } catch (\Exception $e) {
            throw $e;
        }


        //echo "<pre>";
        //var_dump($responseDec);
        //echo "</pre>";
        //exit();
        
        return $responseDec;
    }
    
}