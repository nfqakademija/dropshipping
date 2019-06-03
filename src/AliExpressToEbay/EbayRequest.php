<?php


namespace App\AliExpressToEbay;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use \DTS\eBaySDK\Trading\Types;
use Symfony\Component\Security\Core\Security;
use \DTS\eBaySDK\Exceptions\InvalidPropertyTypeException;
use Symfony\Component\HttpFoundation\Session\Session;

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
    
    private $session;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->session= new Session();
        //$this->session->start();
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->setRequest();
        
    }


    public function setRequest()
    {
        try{
            $this->request = new Types\AddFixedPriceItemRequestType();

            $this->request->RequesterCredentials = new Types\CustomSecurityHeaderType();
            $this->request->RequesterCredentials->eBayAuthToken = $this->getAuthToken();
        } catch (InvalidPropertyTypeException $e) {
            //echo $e->getMessage();
            //dump($this->session);
            //exit();
            //$this->session->getFlashBag()->add('danger',$e->getMessage());
            $this->session->getFlashBag()->add('danger','Error adding item to ebay');
            //throw $e;
        }
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