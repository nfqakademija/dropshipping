<?php


namespace App\Ebay;


class EbayCredentials
{
    /**
     * @var array
     */
    public $config;

    /**
     * EbayCredentials constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param string $type
     * @return mixed
     * @throws \Exception
     */
    public function getConfig($type = 'sandbox')
    {
        if ($type != 'sandbox' && $type != 'production') {
            throw new \Exception('this config type does not exist!');
        }
        return $this->config[$type];
    }


}