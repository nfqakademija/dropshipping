<?php


namespace App\ExternalApi;

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;

class EbayMySelling
{
    public function getMyItems($config, $userOauth)
    {
        $service = new Services\TradingService([
            'credentials' => $config['credentials'],
            'siteId'      => Constants\SiteIds::US,
            'authorization' => $userOauth,
            'sandbox' => true
        ]);

        $request = new Types\GetMyeBaySellingRequestType();
        /**
         * An user token is required when using the Trading service.
         */
        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->DetailLevel = [Enums\DetailLevelCodeType::C_RETURN_ALL];

        $request->ActiveList = new Types\ItemListCustomizationType();
        $request->ActiveList->Include = true;
        $request->ActiveList->Pagination = new Types\PaginationType();
        $request->ActiveList->Pagination->EntriesPerPage = 10;
        $request->ActiveList->Sort = Enums\ItemSortTypeCodeType::C_CURRENT_PRICE_DESCENDING;
        $pageNum = 1;
        $request->ActiveList->Pagination->PageNumber = $pageNum;
        $response = $service->getMyeBaySelling($request);

//        do {
//            $request->ActiveList->Pagination->PageNumber = $pageNum;
//            /**
//             * Send the request.
//             */
//            $response = $service->getMyeBaySelling($request);
//            /**
//             * Output the result of calling the service operation.
//             */
//            echo "==================\nResults for page $pageNum\n==================\n";
//            if (isset($response->Errors)) {
//                foreach ($response->Errors as $error) {
//                    printf(
//                        "%s: %s\n%s\n\n",
//                        $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
//                        $error->ShortMessage,
//                        $error->LongMessage
//                    );
//                }
//            }
//            if ($response->Ack !== 'Failure' && isset($response->ActiveList)) {
//                foreach ($response->ActiveList->ItemArray->Item as $item) {
//                    printf(
//                        "(%s) %s: %s %.2f\n",
//                        $item->ItemID,
//                        $item->Title,
//                        $item->SellingStatus->CurrentPrice->currencyID,
//                        $item->SellingStatus->CurrentPrice->value,
//                        $item->Du
//                    );
//                }
//            }
//            $pageNum += 1;
//        } while (isset($response->ActiveList) && $pageNum <= $response->ActiveList->PaginationResult->TotalNumberOfPages);
        return $response->ActiveList->ItemArray->Item;
    }
}