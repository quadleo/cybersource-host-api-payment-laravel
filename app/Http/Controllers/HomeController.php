<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;
use App\Repositories\CyberSourceRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
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

    public function index()
    {
        $data = [];
        $data['access_key'] = env('ACCESS_KEY');
        $data['profile_id'] = env('PROFILE_ID');
        $data['transaction_uuid'] = Uuid::uuid4()->toString();
        $data['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");
        // dd($data);
        // $data['transaction_uuid'] = "6b88d26a-278e-45b7-a4f5-efec6d8262ii";
        // $data['signed_date_time'] = "2023-03-22T10:21:56Z";
        return view('home', compact('data'));
    }
    public function apiIndex()
    {
        $data = [
            'deviceDataCollectionURL' => config('csservices.live') ? config('csservices.deviceDataCollectionURL_live') : config('csservices.deviceDataCollectionURL'),
            'cardinalCollectionFormOrigin' => config('csservices.live') ? config('csservices.cardinalCollectionFormOrigin_live') : config('csservices.cardinalCollectionFormOrigin'),
            'cardinalStepUpURL' => config('csservices.live') ? config('csservices.cardinalStepUpURL_live') : config('csservices.cardinalStepUpURL'),
        ];
        $data['access_key'] = env('ACCESS_KEY');
        $data['profile_id'] = env('PROFILE_ID');
        $data['transaction_uuid'] = Uuid::uuid4()->toString();
        $data['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");
        // dd($data);
        // $data['transaction_uuid'] = "6b88d26a-278e-45b7-a4f5-efec6d8262ii";
        // $data['signed_date_time'] = "2023-03-22T10:21:56Z";

        return view('cybersource.api.apipay-main', compact('data'));
    }

    
    /* STEP2:- Authentication the payment */
    public function apiPost(Request $request)
    {
        $cardParsedAry =  [
            "clientReferenceInformation" => [
                "code" => "NIC12345"
            ],
            "consumerAuthenticationInformation" => [
                "referenceId" => "64827f74-99fa-4109-842e-0dbfc9738876",
                "returnUrl" => "https://chilaxhouse.com.np/webook/confirm-api-pay-redirect"
            ],
            "processingInformation" => [
                "capture" => true,
                "actionList" => [
                    "CONSUMER_AUTHENTICATION"
                ]
            ],
            "orderInformation" => [
                "billTo" => [
                    "country" => "US",
                    "lastName" => "VDP",
                    "address2" => "Address 2",
                    "address1" => "201 S. Division St.",
                    "postalCode" => "48104-2201",
                    "locality" => "Ann Arbor",
                    "administrativeArea" => "MI",
                    "firstName" => "RTS",
                    "phoneNumber" => "999999999",
                    "district" => "MI",
                    "buildingNumber" => "123",
                    "company" => "Visa",
                    "email" => "test@cybs.com"
                ],
                "amountDetails" => [
                    "totalAmount" => "1.00",
                    "currency" => "NPR"
                ]
            ],
            "paymentInformation" => [
                "card" => [
                    "expirationMonth" => $request->expiration_month,
                    "expirationYear" => $request->expiration_year,
                    "number" => $request->card_number,
                    "securityCode" => $request->security_code,
                    "type" => "001"
                ]
            ]
        ];

        $data = [
            'cardinalStepUpURL' => config('csservices.live') ? config('csservices.cardinalStepUpURL_live') : config('csservices.cardinalStepUpURL'),
            'jwt' =>'1234test'
        ];
        $response = $this->repo->createAuthentication($cardParsedAry);
        Log::info(['CSautheLog'=>$response]);
        if($response['status'] =='PENDING_AUTHENTICATION'){
            $data['jwt'] = $response['authenticationInformation']->accessToken;
            // $data['jwt'] = $response['authenticationInformation']->pareq;    
        }
        elseif ($response['status'] =='AUTHORIZED'){
            $data['transacationID']= $response['authenticationInformation']->authenticationTransactionId;
        }
        
        return view('cybersource.api.auth-iframe', compact('data'));
    }
    
    /* STEP3:- confirm the payment */
    public function apiConfirmPayRedirect(Request $request)
    { 
        
        Log::info(['CSResponseLog'=>$request->all()]);
        $cardParsedAry =  [
            "clientReferenceInformation" => [
                "code" => "NIC12345"
            ],
            "consumerAuthenticationInformation" => [
                "authenticationTransactionId"=>  $request['authenticationInformation']->authenticationTransactionId,
                "directoryServerTransactionId"=> "bf72e834-f633-4a49-87f8-4a61cd522afe"

            ],
            "processingInformation" => [
                "capture" => true,
                "actionList" => [
                    "VALIDATE_CONSUMER_AUTHENTICATION"
                ]
            ],
            "orderInformation" => [
                "billTo" => [
                    "country" => "US",
                    "lastName" => "VDP",
                    "address2" => "Address 2",
                    "address1" => "201 S. Division St.",
                    "postalCode" => "48104-2201",
                    "locality" => "Ann Arbor",
                    "administrativeArea" => "MI",
                    "firstName" => "RTS",
                    "phoneNumber" => "999999999",
                    "district" => "MI",
                    "buildingNumber" => "123",
                    "company" => "Visa",
                    "email" => "test@cybs.com"
                ],
                "amountDetails" => [
                    "totalAmount" => "1.00",
                    "currency" => "NPR"
                ]
            ],
            // "paymentInformation" => [
            //     "card" => [
            //         "expirationMonth" => $request->expiration_month,
            //         "expirationYear" => $request->expiration_year,
            //         "number" => $request->card_number,
            //         "securityCode" => $request->security_code,
            //         "type" => "001"
            //     ]
            // ]
        ];
        $response = $this->repo->makePayment($cardParsedAry);
        Log::info(['CSMakePaymentLog'=>$response]);

        return view('cybersource.api.payment-complete', compact('response'));
    }


    ###################
    ###SAMPLE CODE
    ################
    /* STEP1:- for validation auth */
    public function apiAuthSetupReply()
    {
        $data = [
            'deviceDataCollectionURL' => config('csservices.live') ? config('csservices.deviceDataCollectionURL_live') : config('csservices.deviceDataCollectionURL'),
            'cardinalCollectionFormOrigin' => config('csservices.live') ? config('csservices.cardinalCollectionFormOrigin_live') : config('csservices.cardinalCollectionFormOrigin'),
            'accessToken' =>  'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI0Mjg4NDlkYy01ODJjLTQ5YTUtYTAyYS03NDFjM2I2YTgyMGUiLCJpYXQiOjE2ODk0ODgyMTMsImlzcyI6IjVlMjIwMDVmMzU2ZGNlMDNmMGY3ODcyZiIsImV4cCI6MTY4OTQ5MTgxMywiT3JnVW5pdElkIjoiNjM3NjMwNTQ2ZTE3ODY3YmU2YjdkMzIyIiwiUmVmZXJlbmNlSWQiOiJmMjAyNzIzZi1mMDc1LTQ4MzYtYTc1Yy0zMzZmMmI3NjFlNGQifQ.F2eyQIEqfLflYlpcak7vGX7VfCGdxj0zM4kJX2gmLRQ'
        ];
        return view('cybersource.api.auth-setup-reply', compact('data'));
    }
    /* STEP2:- for validation OTP */
    public function apiAuthSetupUrl()
    {
        $data = [
            'cardinalStepUpURL' => config('csservices.live') ? config('csservices.cardinalStepUpURL_live') : config('csservices.cardinalStepUpURL'),
            'jwt' =>  'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJiYzdiNTY3NC1hNTkxLTRmYTctYWVhNi04YzQwZTNlNGE3N2UiLCJpYXQiOjE2ODk0ODkwNTIsImlzcyI6IjVlMjIwMDVmMzU2ZGNlMDNmMGY3ODcyZiIsImV4cCI6MTY4OTQ5MjY1MiwiT3JnVW5pdElkIjoiNjM3NjMwNTQ2ZTE3ODY3YmU2YjdkMzIyIiwiUGF5bG9hZCI6eyJBQ1NVcmwiOiJodHRwczovL2Fjcy5zMm1nY2MuY29tLzNkc0Fjc0Zyb250T2ZmaWNlV2ViL1ZhbGlkYXRlUGFyYW1WaXNhIiwiUGF5bG9hZCI6ImVOcFZVbHR1d2pBUS9QY3BFQWZBem91WEZrdHBnWUpFQ0FvaFNQeFp5YXFKU2dJNENhVTlmZTFBU212SjBzNnV2WjZaTllTcFJKeHVNYTRsY3ZDd0xNVTdkckprMGczUjI4OXgrSG53aWlwdzEvV3d0b3d1aDQwYjRJWERGV1dablFwdTlGalBCTnBDb2xySU9CVkZ4VUhFbDVmbG10dkRrV1ZiUUIrUVFJNXlPZVVHWXdPOW1WcVdhUUM5cHdrVUlrZStXYmlCdDUxRmJtZnJyM2JoMGw5dk81c29CTnBVQ2NTbnVxamtGM2RNRzJnTENOVHl5Tk9xT284cFBhZEM1aVZlUlM4KzVVQjFoUUI5c3R2VU9pcVY2RnVXOE9ndFdrZnNjUFFYaVFqM0R0dDl6UDBrVEEzdmV6WUJxazhRU0VTRjNHU21wWWozTzZ3L3R0allVY1NiUEFHUmF4bzhLSHZLRTZiMDNqR0JzMzdKdlNORFYvNG1sSlJhU2l6aVZrdUxDT0R0ZkNwUW5WSCsvc1pLdzVQNTYwSzdIRmZLTjdZYUxiMUJhTXhIekJsb3M1dHMweVJUMXBnMnM1b3VXZU1UMVRmcFk1VDBNWFVWL2ZzTlB4WStxL1U9IiwiVHJhbnNhY3Rpb25JZCI6IlRlTVdGZTh3Wk1udFJBTnU4dTMxIn0sIk9iamVjdGlmeVBheWxvYWQiOnRydWUsIlJldHVyblVybCI6Imh0dHBzOi8vNTk4Yy0yMDItMTY2LTE5Ny0yNTMubmdyb2stZnJlZS5hcHAvY3liZXJzb3VyY2UvcmVzcG9uc2UucGhwIn0.E-yTDii81I2vBpOOXMd3rsAkOwStXeoVx7GOPgTcaDE'
        ];

        return view('cybersource.api.auth-setup-url', compact('data'));
    }
}
