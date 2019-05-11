<?php


namespace App\AliExpress;


use AliseeksApi\Model\ProductDetail;
use AliseeksApi\Model\ProductHtmlDescription;
use App\Entity\AliExpressCategory;
use App\Entity\AliExpressItem;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class AliExpressDataSaver
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
     * @var AliExpressItem
     */
    private $item;

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
     * @param ProductDetail $product
     * @param ProductHtmlDescription $productDescription
     */
    public function storeProduct(ProductDetail $product, ProductHtmlDescription $productDescription)
    {
        $item = new AliExpressItem();
        $item->setProductId($product->getId());
        $item->setTitle($product->getTitle());
        $item->setStock($product->getPromotions()[0]->getStock());
        $item->setPrice($product->getPrices()[0]->getMaxAmountPerPiece()->getValue());
        $item->setDescription($productDescription->getDescription());
        $item->setUser($this->getUserId($this->security));
        $item->setCategory($this->findAliExpressCategory($product->getCategoryId()));

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        $this->item = $item;
    }

    /**
     * @param ProductDetail $product
     */
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

    /**
     * @param Security $security
     * @return User
     */
    public function getUserId(Security $security): User
    {
        $user = $security->getUser();
        return $this->entityManager->getRepository(User::class)->find($user->getId());
    }

    /**
     * @param string $id
     * @return string
     */
    public function findAliExpressCategory(string $id): string
    {
        $categoryName = $this->entityManager->getRepository(AliExpressCategory::class)->findOneBy(['categoryId' => $id]);

        if(!empty($categoryName)) {
            return $categoryName->getName();
        }
        return "";
    }
}