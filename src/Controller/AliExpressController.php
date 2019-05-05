<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use AliseeksApi\Configuration;
use AliseeksApi\Api\ProductsApi;
use AliseeksApi\Model\ProductRequest;
use AliseeksApi\Model\ProductDetailsRequest;
use GuzzleHttp\Client;
use AliseeksApi\ApiException;

class AliExpressController extends AbstractController
{
    /**
     * @Route("/ali/express", name="ali_express")
     */
    public function index(int $productId)
    {
        // Configure API key authorization: ApiKeyAuth
        $config = Configuration::getDefaultConfiguration()->setApiKey('X-API-CLIENT-ID', 'TEIEQFRZKOYVNPUL');


        $apiInstance = new ProductsApi(
        // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
        // This is optional, `GuzzleHttp\Client` will be used as default.
            new Client(),
            $config
        );
        $product_request = new ProductDetailsRequest(); // \AliseeksApi\Model\ProductRequest | The request body of get product
        $product_request->setProductId($productId);

        try {
            $result = $apiInstance->getProduct($product_request);
            print_r($result);
        } catch (ApiException $e) {
            echo 'Exception when calling ProductsApi->getProduct: ', $e->getMessage(), PHP_EOL;
        }







        return $this->render('ali_express/index.html.twig', [
            'controller_name' => 'AliExpressController',
        ]);
    }
}
