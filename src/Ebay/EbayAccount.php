<?php


namespace App\Ebay;


use App\ExternalApi\EbayAccounDetails;
use App\ExternalApi\EbaySellerTransactions;
use App\ExternalApi\EbayUserFeedback;

class EbayAccount
{

    public $manager;

    /**
     * EbayAccount constructor.
     * @param EbayManager $ebayManager
     */
    public function __construct(EbayManager $ebayManager)
    {
        $this->manager = $ebayManager;
    }

    /**
     * @param $token
     * @return mixed
     */
    public function getAccountPolicies($token)
    {
        $policies = (new EbayAccounDetails)
            ->getPolicies(
                $this->manager->businessPoliciesProvider($this->manager->businessService, $token)
            );

        return $policies;
    }

    /**
     * @param $token
     * @return mixed
     */
    public function getUserFeedbacks($token)
    {
        $feedback = (new EbayUserFeedback)
            ->getFeedback($this->manager->tradingProvider($this->manager->tradingService, $token),
                $token
            );

        return $feedback;
    }

    /**
     * @param $token
     * @return EbaySellerTransactions
     */
    public function getTransactionsDetails($token)
    {
        $transaction = new EbaySellerTransactions(
            $this->manager->tradingProvider($this->manager->tradingService),
            $token
        );

        return $transaction;
    }
}