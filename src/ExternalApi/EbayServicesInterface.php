<?php


namespace App\ExternalApi;


interface EbayServicesInterface
{
    public function getServices($config, $type);
}