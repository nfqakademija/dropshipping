<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;


class DashboardController extends AbstractController
{

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser()->getFirstName();

        $config = [
            'siteId' => '0',

            'sandbox' => [
                'credentials' => [
                    'devId' => '0f7b9716-8cac-4b7f-b968-524b3e5d3f04',
                    'appId' => 'MantasPu-codezipk-SBX-555b8f5d2-321e0c95',
                    'certId' => 'SBX-55b8f5d2c56e-055f-41bd-a378-4287',
                ],
                'authToken' => 'AgAAAA**AQAAAA**aAAAAA**Bge+XA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4aiCJeFpQSdj6x9nY+seQ**r/IEAA**AAMAAA**zqrDMlnBLwIby9V01+9IHpJuaJ6cRm9tgPyujCF4lcfROs5aPqTdMudrHXaCm1irJGTnaRsYwMWQ1/B+iEFykCh7AfUVF/C9szV8WVqdm0kA4AcsopICBxw9P3/esZxLIgq+qR5Olil86fNW+ITWualPxGLl3YjW7XkACzRlemJ/NibzrFzkBAIyKbJSyhZZwUeOApXEFnB6pbT15/dVLC+BKhdPYpC4h9n1u3MGrhxTAyzxFGPr2hLMpUA0KSyIx1m74Ipumn4L6JZbpvL9sLQUNIVDBd9NUqCCcgO2xGcwKPNXYEg18wJJXwZijFIRJZ3QI6ov4XvC47wDBozj7Bfnl4fU/WV+K52yaGjIU6VB3k+XhDxsSEsVWCUt84FymlNF3avwpAM4ELGnheEY7cS6/eca/b15TMoqjmkaF9Y3eC9w1JTzEWkOK1Y/h7Qf6+RK41xSRZWBMvZGqjLxhQTzSSX0FqzCz+r+PLd8XfFIgrqDcvoc5uHZMqUJ5o59bK6/QxLXPrgrmjZbi6UcsRvhbQw3TLGw8yVPh6KweNI5m+d7L/aY3OkYIyKZAYxiMC9lXJNQJtNZcksEbyFOEg5fj0/BaErzixKBnXMBgwI3czUQEjgPqNbs893k7yz05+YTvfYxY4/w0jCDrcUjnSRf1Bk3Dj1mIAmQLktyJxdDmbeeXrCDbmxwk6SFskSLYmGpVdyRgiDrdPbnm3zhRrBE85+ahuEo9YvHGn5Jo2XvBkPdj3mJFxLEZ3tjFvY9',
                'oauthUserToken' => 'v^1.1#i^1#r^0#p^1#f^0#I^3#t^H4sIAAAAAAAAAOVXbWwURRju9Qsq5SNqQAqEY9FEMbc3c3t7Hys9OCjIRUpPrpBCJGRvd7Zdul/ZmbUtSHLpD9BgEAVNIMEcCRp+GLAkKiREookpxAgEUjUSA4qQ/lD8AAWjonN7R7lWAhQOIfH+XOadd955nud935kdkKmumbFuwbpLoz0jyrMZkCn3eOAoUFNd9eSYivK6qjJQ5ODJZh7NVHZX9M/Eoq5ZwmKELdPAyNupawYWXGM949iGYIpYxYIh6ggLRBJS8caFQoAFgmWbxJRMjfEmGuqZKARcCEI5HIQcDCuAWo2rMZvNekaMBqQQH+FDSigMAmGOzmPsoISBiWiQeiYAYNQHgr5AoBmGBcALfJAFoeByxrsU2Vg1DerCAibmwhXctXYR1htDFTFGNqFBmFgiPj/VFE80zFvUPNNfFCtW0CFFROLgwaO5poy8S0XNQTfeBrveQsqRJIQx44/ldxgcVIhfBXMb8F2pgxwXgArggYL4EM8pJZFyvmnrIrkxjpxFlX2K6yogg6ik62aKUjXSq5BECqNFNESiwZv7e9YRNVVRkV3PzJsTXxZPJplYIyUg4qTjk6jkq1Wr3Zdc3OAL8XxaQREk+rgIF5RRFBY2ykcryDxkp7mmIas50bB3kUnmIIoaDdYmJPBF2lCnJqPJjiskh6jYL3pVQ55bnktqPosOaTNyeUU6FcLrDm+egYHVhNhq2iFoIMLQCVci2jaWpcrM0Em3Fgvl04nrmTZCLMHv7+joYDs41rRb/QEAoL+lcWFKakO6yFDfXK/n/dWbL/CpLhUJ0ZVYFUiXRbF00lqlAIxWJsZFwhEQLug+GFZsqPVfhiLO/sEdUaoOgQoXApAPh8JpCGEgWIoOiRWK1J/DgdJil08X7XZELE2UEK1XAzs6slVZ4HglwEUU5JNDUcUXjCqKL83LIR9UEAIIpdNSNPJ/apRbLfWUZFooaWqq1FWSgi9dsdtyUrRJVwppGjXcatVflyTOkbzr9HK9PiyKuRiYBhEtlc3VNiuZut8U6aGWM610Ud8R77hlJXTdIWJaQ4nSHGj36DC7Lj2VXvf3FSeav3wiVTl/T7NuNln8vMTaCJuOTT9R2KbctdVstiODHgLENjUN2UvhHSf6PsvvMM/K2+Nduot6mLxprwfuam1LmkpLaOW9YXePs6qK5P5iDXmej/KBMB+8I15z3Zw2d/0Hd9Gw6C0wMUHyXfiu9A9+5cbK3B/s9rwHuj099KEM/OAxOB1Mq65YUllRW4dVglhVVFisthr08WYjth11WaJql1d71NeOr+8reldnV4BHBl7WNRVwVNEzG0y+NlMFx04YDaMgGAjAMOD54HIw/dpsJRxf+fBDQW9FUt604lLVuNiWNbhsivzhATB6wMnjqSqr7PaUaTtXX16/v25Gb3bk2erTR9/86eUf+k/0Ttq5O3bk7Z4rn1458y6zec5h3Zy1Z8/kk1pi39bXW/b+zca37zv23AluW529ZeVI/bPaONezu2OHvFFs+T7xxajLvdnDf/atrzmuR3aZMzKfPNXN/7Xq3MmTG2d9kLQmvJB4fINwdGrZi2suLOuunXJ+4uSvf/5Omta7sGU/md0ZXdt/ekRTzR8Hpv54aOxB55VvYn2/jPx1b+arg59Ph08/+NsV8iXc+uqk0Lij2QvnNkw5e37cWxff2NR65NvtcmPr2iWHJh57p/+BrS0H3jdPqbvOeBfs3zjtpRH2E8+Mn5Xt2bGkDfZhZ+zsj7ds+2jMxam1p37fnE/fP0NHg8LxEAAA'
            ]
        ];

//        $service = new Services\TradingService([
//            'apiVersion' => '951',
//            'credentials' => $config['sandbox']['credentials'],
//            'siteId'      => Constants\SiteIds::US
//        ]);

        $service = new Services\TradingService([
            'apiVersion'    => '967',
            'credentials' => $config['sandbox']['credentials'],
            'siteId'      => Constants\SiteIds::US
        ]);
        /**
         * Create the request object.
         */
        $request = new Types\GetMyeBaySellingRequestType();
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $config['sandbox']['authToken'];

        $request->ActiveList = new Types\ItemListCustomizationType();
        $request->ActiveList->Include = true;
        $request->ActiveList->Pagination = new Types\PaginationType();
        $request->ActiveList->Pagination->EntriesPerPage = 10;
        $request->ActiveList->Sort = Enums\ItemSortTypeCodeType::C_CURRENT_PRICE_DESCENDING;

        $pageNum = 1;

        do {
            $request->ActiveList->Pagination->PageNumber = $pageNum;
            /**
             * Send the request.
             */
            $response = $service->getMyeBaySelling($request);
            /**
             * Output the result of calling the service operation.
             */
            echo "==================\nResults for page $pageNum\n==================\n";
            if (isset($response->Errors)) {
                foreach ($response->Errors as $error) {
                    printf(
                        "%s: %s\n%s\n\n",
                        $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                        $error->ShortMessage,
                        $error->LongMessage
                    );
                }
            }
            if ($response->Ack !== 'Failure' && isset($response->ActiveList)) {
                foreach ($response->ActiveList->ItemArray->Item as $item) {
                    printf(
                        "(%s) %s: %s %.2f\n",
                        $item->ItemID,
                        $item->Title,
                        $item->SellingStatus->CurrentPrice->currencyID,
                        $item->SellingStatus->CurrentPrice->value
                    );
                }
            }
            $pageNum += 1;
        } while (isset($response->ActiveList) && $pageNum <= $response->ActiveList->PaginationResult->TotalNumberOfPages);

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => $user,
        ]);
    }
}
