<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\OAuth\Services;
use \DTS\eBaySDK\OAuth\Types;
use App\Entity\User;


class EbayOauth extends EntityRepository
{
    private $config;

    protected $orm;

    public function __construct($sandbox)
    {
        $this->config = $sandbox;

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
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($userId);
//
        var_dump($user);

        printf("\nStatus Code: %s\n\n", $response->getStatusCode());
        if ($response->getStatusCode() !== 200) {
            printf(
                "%s: %s\n\n",
                $response->error,
                $response->error_description
            );
        } else {
            $_SESSION['userOauthToken'] = $response->access_token;
            $_SESSION['userOauthTokenType'] = $response->token_type;
            $_SESSION['userOauthTokenExpires'] = $response->expires_in;
            $_SESSION['userOauthTokenRefresh'] = $response->refresh_token;

            $successful = 'User Authenticate Succesful';
        }

//        var_dump($userId);

        return $successful;
    }
}