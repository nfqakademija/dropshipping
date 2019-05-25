<?php


namespace App\ExternalApi;


interface EbayServicesInterface
{
    /**
     * @param $config
     * @param $token
     * @param $type
     * @return mixed
     */
    public function getServices($config, $token, $type);
}