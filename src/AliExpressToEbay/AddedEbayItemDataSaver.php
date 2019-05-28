<?php


namespace App\AliExpressToEbay;


use App\Entity\AliExpressItem;
use App\Entity\User;
use App\Entity\EbayItem;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class AddedEbayItemDataSaver
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
     * AliExpressDataSaver constructor.
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
        if ($origin === 'aliexpress') {
            $item = $this->entityManager->getRepository(AliExpressItem::class)->find($productId);
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
        $ebayItem->setUser($this->getUserId());
        $ebayItem->setCreatedAt(new DateTime("now"));

        $this->entityManager->persist($ebayItem);
        $this->entityManager->flush();
    }

    /**
     * @return User
     */
    public function getUserId(): User
    {
        $user = $this->security->getUser();
        return $this->entityManager->getRepository(User::class)->find($user->getId());
    }
}