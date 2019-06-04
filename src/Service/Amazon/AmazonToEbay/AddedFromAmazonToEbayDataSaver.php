<?php

namespace App\Service\Amazon\AmazonToEbay;


use App\Entity\AmazonItem;
use App\Entity\EbayItem;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class AddedFromAmazonToEbayDataSaver
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Security
     */
    private $security;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        
        $this->entityManager = $entityManager;
        
        $this->security = $security;
        
         
    }

    /**
     * @param int $productId
     * @param int $ebayId
     * @param string $origin
     */
    public function saveEbayItem(int $productId, int $ebayId, string $origin)
    {
        
        $this->changeState($productId, $origin);
        $this->storeEbayItem($productId, $ebayId, $origin);
    }

    /**
     * @param int $productId
     * @param string $origin
     */
    public function changeState(int $productId, string $origin)
    {
        if ($origin === 'amazon') {
            $item = $this->entityManager->getRepository(AmazonItem::class)->find($productId);
            $item->setActive(false);
            $this->entityManager->persist($item);
            $this->entityManager->flush();
        }
    }

    /**
     * @param int $productId
     * @param int $ebayId
     * @param string $origin
     * @throws \Exception
     */
    public function storeEbayItem(int $productId, int $ebayId, string $origin)
    {
        $ebayItem = new EbayItem();
        $ebayItem->setEbayId($ebayId);
        $ebayItem->setProductId($productId);
        $ebayItem->setOrigin($origin);
        $ebayItem->setUser($this->getUser()->getId());
        $ebayItem->setCreatedAt(new DateTime("now"));

        $this->entityManager->persist($ebayItem);
        $this->entityManager->flush();
    }
}