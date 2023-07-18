<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseApiCybersourceController extends Controller
{
    /* Generate Headers constants*/
    protected $sha256digest = 'SHA-256=';
    protected $hmacsha256 = "HmacSHA256";
    protected $signature = "Signature:";
    protected $postalgoheader = "host date (request-target) digest v-c-merchant-id";
    protected $sha256 = "sha256";

    

    protected function generateSignatureTokenHeader($signatureString, $headerString, $merchantConfig)
    {
        $signatureByteString = utf8_encode($signatureString);
        $decodeKey = base64_decode($merchantConfig['secretKey']);
        $signature = base64_encode(hash_hmac($this->sha256, $signatureByteString, $decodeKey, true));
        $signatureHeader = array(
            'keyid="'.$merchantConfig['key'].'"',
            'algorithm="'.$this->hmacsha256.'"',
            'headers="'.$headerString.'"',
            'signature="'.$signature.'"'
        );
        return $this->signature.implode(", ", $signatureHeader);
    }


    protected function generateDigest($payLoad)
    {
        $utf8EncodedString = utf8_encode($payLoad);
        $digestEncode = hash("sha256", $utf8EncodedString, true);
        return base64_encode($digestEncode);
    }

    
    protected function getMerchantConfig($env = 'test')
    {
        return [
            // Real Keys
            // 'key' => config("nicasia.".$env.".merchant_key"),
            // 'secretKey' => config("nicasia.".$env.".merchant_secret_key"),
            // 'id' => config("nicasia.".$env.".merchant_id")
            'key' => env('MERCHANT_KEY'),
            'secretKey' => env('MERCHANT_SECRET_KEY'),
            'id' => env('MERCHANT_ID')
        ];
    }


}
