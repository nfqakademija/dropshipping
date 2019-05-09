<?php


namespace App\AliExpress;


use AliseeksApi\Model\ProductDetail;
use AliseeksApi\Model\ProductHtmlDescription;
use App\Entity\AliExpressItem;
use App\Entity\Image;
use App\Entity\Item;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class AliExpressDataSaver
{
    private $entityManager;

    private $security;

    private $item;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function storeProduct(ProductDetail $product, ProductHtmlDescription $productDescription)
    {
        $item = new AliExpressItem();
        $item->setProductId($product->getId());
        $item->setTitle($product->getTitle());
        $item->setStock($product->getPromotions()[0]->getStock());
        $item->setPrice($product->getPrices()[0]->getMaxAmountPerPiece()->getValue());
        $item->setDescription($productDescription->getDescription());
        $item->setUser($this->getUserId($this->security));

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        $this->item = $item;
    }

    public function storeImages(ProductDetail $product)
    {
        $images = $product->getProductImages();

        foreach ($images as $img) {
            $image = new Image();
            $image->setAliExpressProductId($this->item);
            $image->setImageLink($img);
            $this->entityManager->persist($image);
        }
        $this->entityManager->flush();
    }

    public function getUserId(Security $security) {
        $user = $security->getUser();
        return $this->entityManager->getRepository(User::class)->find($user->getId());
    }
}