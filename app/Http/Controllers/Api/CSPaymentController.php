<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ExternalConfiguration;
use App\Http\Controllers\Controller;
use CyberSource\Api\PaymentsApi;
use CyberSource\ApiClient;
use CyberSource\ApiException;
use CyberSource\Model\CreatePaymentRequest;
use CyberSource\Model\Invoicingv2invoicesOrderInformationAmountDetails;
use CyberSource\Model\Invoicingv2invoicesOrderInformationAmountDetailsFreight;
use CyberSource\Model\Invoicingv2invoicesOrderInformationAmountDetailsTaxDetails;
use CyberSource\Model\Invoicingv2invoicesOrderInformationLineItems;
use CyberSource\Model\Ptsv2paymentsClientReferenceInformation;
use CyberSource\Model\Ptsv2paymentsConsumerAuthenticationInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsOrderInformationBillTo;
use CyberSource\Model\Ptsv2paymentsPaymentInformation;
use CyberSource\Model\Ptsv2paymentsPaymentInformationCard;
use CyberSource\Model\Ptsv2paymentsProcessingInformation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CSPaymentController extends BaseApiCybersourceController
{
    protected $flag = false;
    public function postCheckout(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'number' => ['required', 'int'],
                'expiration_month' => ['required', 'numeric'],
                'expiration_year' => ['required', 'int'],
                'total_amount' => ['required', 'numeric'],
                'currency' => ['required', 'string'],
                'first_name' => ['required'],
                'last_name' => ['required'],
                'address1' => ['required'],
                'locality' => ['required'],
                'administrative_area' => ['required'],
                'postal_code' => ['required', 'numeric'],
                'country' => ['required'],
                'email' => ['required', 'email'],
                'phone_number' => ['required']
            ]);
        } catch (ValidationException $validationException) {
            return $this->sendError($validationException->getMessage(), $validationException->errors());
        }

        try {
            $this->flag === true ? $capture = true : $capture = false;
            $clientReferenceInformationArr = [
                "code" => time()
            ];
            $clientReferenceInformation = new Ptsv2paymentsClientReferenceInformation($clientReferenceInformationArr);

            $processingInformationArr = [
                "capture" => $capture
            ];
            $processingInformation = new Ptsv2paymentsProcessingInformation($processingInformationArr);

            $paymentInformationCardArr = [
                "number" => $validated['number'],
                "expirationMonth" => $validated['expiration_month'],
                "expirationYear" => $validated['expiration_year']
            ];
            $paymentInformationCard = new Ptsv2paymentsPaymentInformationCard($paymentInformationCardArr);

            $paymentInformationArr = [
                "card" => $paymentInformationCard
            ];
            $paymentInformation = new Ptsv2paymentsPaymentInformation($paymentInformationArr);

            $orderInformationAmountDetailsArr = [
                "totalAmount" => $validated['total_amount'],
                "currency" => $validated['currency']
            ];
            $orderInformationAmountDetails = new Ptsv2paymentsOrderInformationAmountDetails($orderInformationAmountDetailsArr);

            $orderInformationBillToArr = [
                "firstName" => $validated['first_name'],
                "lastName" => $validated['last_name'],
                "address1" => $validated['address1'],
                "locality" => $validated['locality'],
                "administrativeArea" => $validated['administrative_area'],
                "postalCode" => $validated['postal_code'],
                "country" => $validated['country'],
                "email" => $validated['email'],
                "phoneNumber" => $validated['phone_number']
            ];
            $orderInformationBillTo = new Ptsv2paymentsOrderInformationBillTo($orderInformationBillToArr);

            $orderInformationArr = [
                "amountDetails" => $orderInformationAmountDetails,
                "billTo" => $orderInformationBillTo
            ];
            $orderInformation = new Ptsv2paymentsOrderInformation($orderInformationArr);

            $requestObjArr = [
                "clientReferenceInformation" => $clientReferenceInformation,
                "processingInformation" => $processingInformation,
                "paymentInformation" => $paymentInformation,
                "orderInformation" => $orderInformation
            ];
            $requestObj = new CreatePaymentRequest($requestObjArr);

            $commonElement = new ExternalConfiguration();
            dd($commonElement);
            $config = $commonElement->ConnectionHost();
            $merchantConfig = $commonElement->merchantConfigObject();

            $api_client = new ApiClient($config, $merchantConfig);
            $api_instance = new PaymentsApi($api_client);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
        try {
            $apiResponse = $api_instance->createPayment($requestObj);
            // return $this->sendResponse(new CybersourceResource($apiResponse[0]), 'Successfully.');

        } catch (ApiException $e) {
            return $this->sendError($e->getMessage(), $e->getResponseBody());
        }
    }
    public function makePayment(Request $request)
    {

        $clientReferenceInformation = new Ptsv2paymentsClientReferenceInformation($request->clientReferenceInformation);
        $processingInformation = new Ptsv2paymentsProcessingInformation($request->processingInformation);
        $paymentInformation = new Ptsv2paymentsPaymentInformation($request->paymentInformation);
        $orderInformation = new Ptsv2paymentsOrderInformation($request->orderInformation);
        $consumerAuthenticationInformation = new Ptsv2paymentsConsumerAuthenticationInformation($request->consumerAuthenticationInformation);
   
        $requestObjArr = [
            "clientReferenceInformation" => $clientReferenceInformation,
            "processingInformation" => $processingInformation,
            "paymentInformation" => $paymentInformation,
            "orderInformation" => $orderInformation,
            "consumerAuthenticationInformation" => $consumerAuthenticationInformation
        ];
        $requestObj = new CreatePaymentRequest($requestObjArr);

        $commonElement = new ExternalConfiguration();
        $config = $commonElement->ConnectionHost();
        $merchantConfig = $commonElement->merchantConfigObject();

        $api_client = new ApiClient($config, $merchantConfig);
        $api_instance = new PaymentsApi($api_client);

        try {
            $apiResponse = $api_instance->createPayment($requestObj);
            $data['id'] = $apiResponse[0]['id'];
            $data['status'] = $apiResponse[0]['status'];
            $data['clientInfomation'] = json_decode($apiResponse[0]['consumerAuthenticationInformation']);
            $data['errorInformation'] = json_decode($apiResponse[0]['errorInformation']);
            return $this->success($data);

        } catch (ApiException $e) {
            print_r($e->getResponseBody());
            print_r($e->getMessage());
            $errorCode = $e->getMessage();
            return $this->error($errorCode);
        }
    }
}
