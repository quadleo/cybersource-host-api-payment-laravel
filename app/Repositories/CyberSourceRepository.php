<?php

namespace App\Repositories;

use App\Helpers\ExternalConfiguration;
use CyberSource\Api\PayerAuthenticationApi;
use CyberSource\Api\PaymentsApi;
use CyberSource\ApiClient;
use CyberSource\ApiException;
use CyberSource\Model\CreatePaymentRequest;
use CyberSource\Model\PayerAuthSetupRequest;
use CyberSource\Model\Ptsv2paymentsClientReferenceInformation;
use CyberSource\Model\Ptsv2paymentsConsumerAuthenticationInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsOrderInformationBillTo;
use CyberSource\Model\Ptsv2paymentsPaymentInformation;
use CyberSource\Model\Ptsv2paymentsPaymentInformationCard;
use CyberSource\Model\Ptsv2paymentsProcessingInformation;
use CyberSource\Model\Riskv1authenticationsetupsPaymentInformation;
use CyberSource\Model\Riskv1authenticationsetupsPaymentInformationCard;
use CyberSource\Model\Riskv1decisionsClientReferenceInformation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CyberSourceRepository
{
    public function createAuthSetup($data)
    {
        $clientReferenceInformation = new Riskv1decisionsClientReferenceInformation($data['clientReferenceInformation']);
        $paymentInformation = new Riskv1authenticationsetupsPaymentInformation($data['paymentInformation']);

        $requestObjArr = [
            "clientReferenceInformation" => $clientReferenceInformation,
            "paymentInformation" => $paymentInformation
        ];
        $requestObj = new PayerAuthSetupRequest($requestObjArr);
        $commonElement = new ExternalConfiguration();
        $config = $commonElement->ConnectionHost();
        $merchantConfig = $commonElement->merchantConfigObject();

        $api_client = new ApiClient($config, $merchantConfig);
        $api_instance = new PayerAuthenticationApi($api_client);

        try {
            $apiResponse = $api_instance->payerAuthSetup($requestObj);
            $data['status'] = $apiResponse[0]['status'];
            $data['clientInfomation'] = json_decode($apiResponse[0]['consumerAuthenticationInformation']);
            $data['errorInformation'] = json_decode($apiResponse[0]['errorInformation']);

            return $data;
        } catch (ApiException $e) {
            // print_r($e->getResponseBody());
            // print_r($e->getMessage());
            // print_r($e->getResponseBody());
            // print_r($e->getMessage());
            $errorCode = $e->getMessage();
            return $errorCode;
        }
    }
    public function createAuthentication($data)
    {
        $clientReferenceInformation = new Ptsv2paymentsClientReferenceInformation($data['clientReferenceInformation']);
        $processingInformation = new Ptsv2paymentsProcessingInformation($data['processingInformation']);
        $paymentInformation = new Ptsv2paymentsPaymentInformation($data['paymentInformation']);
        $orderInformation = new Ptsv2paymentsOrderInformation($data['orderInformation']);
        $consumerAuthenticationInformation = new Ptsv2paymentsConsumerAuthenticationInformation($data['consumerAuthenticationInformation']);

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
            $data['authenticationInformation'] = json_decode($apiResponse[0]['consumerAuthenticationInformation']);
            $data['errorInformation'] = json_decode($apiResponse[0]['errorInformation']);

            return $data;
        } catch (ApiException $e) {
            // print_r($e->getResponseBody());
            // print_r($e->getMessage());
            $errorCode = $e->getMessage();
            return $errorCode;
        }
    }

    
    public function makePayment($data)
    {
        $clientReferenceInformation = new Ptsv2paymentsClientReferenceInformation($data['clientReferenceInformation']);
        $processingInformation = new Ptsv2paymentsProcessingInformation($data['processingInformation']);
        // $paymentInformation = new Ptsv2paymentsPaymentInformation($data['paymentInformation']);
        $orderInformation = new Ptsv2paymentsOrderInformation($data['orderInformation']);
        $consumerAuthenticationInformation = new Ptsv2paymentsConsumerAuthenticationInformation($data['consumerAuthenticationInformation']);
   
        $requestObjArr = [
            "clientReferenceInformation" => $clientReferenceInformation,
            "processingInformation" => $processingInformation,
            // "paymentInformation" => $paymentInformation,
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
            return $data;

        } catch (ApiException $e) {
            print_r($e->getResponseBody());
            print_r($e->getMessage());
            $errorCode = $e->getMessage();
            return $errorCode;
        }
    }
}

?>