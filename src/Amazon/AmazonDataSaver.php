<?php


namespace App\Amazon;

use App\Entity\AmazonItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\AmazonImage;


class AmazonDataSaver
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
     * AmazonDataSaver constructor.
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    
    /**
     * @param $product
     */
    public function storeProduct(array $product)
    {
        $item = new AmazonItem();
        //$item->setProductId($product->getId());
        $item->setProductId($product[0]['asin']);
        $item->setTitle($product[0]['product_name']);
        $item->setStock($product[0]['product_availability']);
        $item->setPrice($product[0]['product_sale_price']);
        $item->setDescription($product[0]['description']);
        $item->setUser($this->security->getUser());
        $item->setCategory($product[0]['product_category']);
        $item->setImageUrl($product[0]['image_1']);
        $item->setCrawled(new \DateTime($product[0]['created']));
        $item->setCreated(new \DateTime());

        $this->entityManager->persist($item);
        $this->entityManager->flush();
        return $item;
        
    }
    
    public function storeImages(array $product, AmazonItem $item)
    {
        $images[] = $product[0]['image_1'];

        foreach ($images as $img) {
            $image = new AmazonImage();
            $image->setAmazonProductId($item);
            $image->setImageLink($img);
            $this->entityManager->persist($image);
        }
        $this->entityManager->flush();
    }
    
}