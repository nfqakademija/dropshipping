<?php


namespace App\AliExpressToEbay;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use \DTS\eBaySDK\Trading\Types;
use Symfony\Component\Security\Core\Security;

class EbayRequest
{
    private $request;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Security
     */
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->setRequest();
    }


    public function setRequest()
    {
        $this->request = new Types\AddFixedPriceItemRequestType();

        $this->request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $this->request->RequesterCredentials->eBayAuthToken = $this->getAuthToken();
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getAuthToken()
    {
        $user = $this->security->getUser();
        return $this->entityManager->getRepository(User::class)->find($user->getId())->getOldEbayAuth();
    }
}