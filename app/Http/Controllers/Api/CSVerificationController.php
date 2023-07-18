<?php

namespace App\Http\Controllers\Api;

use App\Repositories\CyberSourceRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CSVerificationController extends BaseApiCybersourceController
{
    
    protected $request;
    protected $repo;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, CyberSourceRepository $repo)
    {
        $this->request = $request;
        $this->repo = $repo;
    }

    public function authSetup(Request $request)
    {        

        $data['clientReferenceInformation'] = [
            "code" => "cybs_test"
        ];
        $data['paymentInformation'] = [
            'card' => [                
                "type" => $request->type,
                "expirationMonth" => $request->expiration_month,
                "expirationYear" => $request->expiration_year,
                "number" => $request->card_number
            ]
        ];

        try { 
            $response = $this->repo->createAuthSetup($data);
            return $this->success($response);

        } catch (Exception $e) {
            // print_r($e->getResponseBody());
            // print_r($e->getMessage());
            // print_r($e->getResponseBody());
            // print_r($e->getMessage());
            $errorCode = $e->getMessage();
            return $this->error($errorCode);
        }

    }
    public function authVerification(Request $request)
    {
        /* sample request data */
        $cardParsedAry = [
            "clientReferenceInformation" => [
                "code" => "cybs_visa_3"
            ],
            "orderInformation" => [
                "amountDetails" => [
                    "currency" => "USD",
                    "totalAmount" => "100"
                ],
                "billTo" => [
                    "address1" => "901 metro center blvd",
                    "address2" => "metro 3",
                    "administrativeArea" => "CA",
                    "country" => "US",
                    "locality" => "san francisco",
                    "firstName" => "John",
                    "lastName" => "Doe",
                    "phoneNumber" => "18007097779",
                    "postalCode" => "94404",
                    "email" => "email@email.com"
                ]
            ],
            "paymentInformation" => [
                "card" => [
                    "type" => "001",
                    "expirationMonth" => "12",
                    "expirationYear" => "2027",
                    "number" => "4456530000001096"
                ]
            ],
            "buyerInformation" => [
                "mobilePhone" => "1245789632"
            ],
            "consumerAuthenticationInformation" => [
                "referenceId" => "222132d0-f2d1-4971-ba2f-6b444d9ee438",
                "transactionMode" => "S",
                "returnUrl" => "https://cybs-api.ngrok.io/response.php"
            ]
        ];

        try { 
            $response = $this->repo->createAuthentication($cardParsedAry);
            return $this->success($response);

        } catch (Exception $e) {
            // print_r($e->getResponseBody());
            // print_r($e->getMessage());
            // print_r($e->getResponseBody());
            // print_r($e->getMessage());
            $errorCode = $e->getMessage();
            return $this->error($errorCode);
        }

    }
    //verification code is here
    public function verifyPost(Request $request)
    {
        $refCode = $request->req_reference_number;
        $merchantConfig = $this->getMerchantConfig();
        $url = env('VERIFICATION_URL') . "/tss/v2/searches";
        $host = env('HOST');


        $requestObjArr = [
            "save" => false,
            "name" => "asd",
            "timezone" => "Asia/Kathmandu",
            "query" => "clientReferenceInformation.code:" . $refCode . ' AND submitTimeUtc:[NOW/DAY-7DAYS TO NOW/DAY+1DAY}',
            "offset" => 0,
            "limit" => 100,
            "sort" => "id:asc,submitTimeUtc:asc"
        ];

        $signatureGeneration = [
            'host' => $host,
            'date' => gmdate('D, d M Y H:i:s T'),
            'methodHeader' => "post",
            'resourcePath' => "/tss/v2/searches"
        ];
        try {
            /**
             * Never touch these methods they are taken from docs to generate token
             * these are magic functions.
             * take reference from the repo
             *
             * https://github.com/CyberSource/cybersource-rest-samples-php
             *
             *
             */
            $signatureGeneration['digest'] = $this->generateDigest(json_encode($requestObjArr, JSON_PRETTY_PRINT));
            $signatureString = "host: " . $signatureGeneration['host'] . "\ndate: " . $signatureGeneration['date'] . "\n(request-target): " . $signatureGeneration['methodHeader'] . " " . $signatureGeneration['resourcePath'] . "\ndigest: " . $this->sha256digest . $signatureGeneration['digest'] . "\nv-c-merchant-id: " . $merchantConfig['id'];
            $token = $this->generateSignatureTokenHeader($signatureString, $this->postalgoheader, $merchantConfig);
            $headers = explode(PHP_EOL, $signatureString);
            $headers = array_merge($headers, [$token, "Content-Type: application/json"]);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($requestObjArr, JSON_PRETTY_PRINT),
                CURLOPT_HTTPHEADER => $headers,
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response, true);
            dd($response);

            Log::error($response);

            // if (!isset($response['_embedded'])) {
            //     $order->payment_status = 'pending';
            //     $order->save();
            //     throw new \Exception("There was error verifying your payment.");
            // }
            $searchResults = $response['_embedded']['transactionSummaries'];
            // DB::beginTransaction();

            if (count($searchResults)) {
                foreach ($searchResults as $result) {
                    $transactionDetail = $result;

                    $amountDetail = $transactionDetail['orderInformation']['amountDetails'];
                    // if ($transactionDetail['clientReferenceInformation']['code'] != $order->order_number) {
                    //     continue;
                    // }
                    //  Total Amount Is Paid
                    // if (isset($amountDetail['totalAmount']) && round($order->total) <= round($amountDetail['totalAmount'])) {
                    //     // MArk order as paid
                    //     $order->payment_status = 'paid';
                    //     $order->payment_method_id = 'This is Payment Method Id';
                    //     $order->save();
                    //     // Create Transaction result here
                    // }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception("There was error verifying your payment.");
        }
    }
   
}
