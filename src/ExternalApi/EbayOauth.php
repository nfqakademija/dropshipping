<?php


namespace App\ExternalApi;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\OAuth\Services;
use \DTS\eBaySDK\OAuth\Types;
use App\Entity\User;


class EbayOauth
{
    private $config;

    public $entity;

    public $userId;

    public function __construct($sandbox, $entityManager, $userId)
    {
        $this->config = $sandbox;

        $this->entity = $entityManager;

        $this->userId = $userId;

        $user = $this->entity->getRepository(User::class)->find($this->userId);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found with id '.$userId
            );
        }

        $requestCode = Request::createFromGlobals();

        $successful = '';

        $service = new Services\OAuthService([
            'credentials' => $this->config[0]['credentials'],
            'ruName'      => $this->config[3]['ruName'],
            'sandbox'     => true
        ]);
        /**
         * Create the request object.
         */
        $request = new Types\GetUserTokenRestRequest();
        $request->code = $requestCode->get('code');
        /**
         * Send the request.
         */
        $response = $service->getUserToken($request);

        /**
         * Output the result of calling the service operation.
         */
        if ($response->getStatusCode() !== 200) {
            printf(
                "%s: %s\n\n",
                $response->error,
                $response->error_description
            );
        } else {
//            $_SESSION['userOauthToken'] = $response->access_token;
//            $_SESSION['userOauthTokenRefresh'] = $response->refresh_token;
            $_SESSION['userOauthTokenType'] = $response->token_type;
            $_SESSION['userOauthTokenExpires'] = $response->expires_in;

            if ( !is_null($user->getOauthToken()) && !is_null($user->getOauthRefreshToken()) ) {
                throw new \Exception("User Ebay token already exists...");
            }
            $user->setOauthToken($response->access_token);
            $user->setOauthRefreshToken($response->refresh_token);
            $entityManager->flush();
            $successful = 'User Authenticate Succesful';
        }

        return $successful;

    }
}