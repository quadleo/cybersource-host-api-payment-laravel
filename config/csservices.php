<?php

return [
    'live' => env('CS_ENV',true),
    'merchant_id' => env('MERCHANT_ID'),
    'apikey_id' => env('APIKEY_ID'),
    'secret_key' => env('SECRET_KEY'),
    'run_env' => env('RUN_ENV'),
    'key_alias' => env('KEY_ALIAS'),
    'key_pass' => env('KEY_PASS'),
    'key_file_name' => env('KEY_FILE_NAME'),
    // Payment ---------- //
    'deviceDataCollectionURL'      => 'https://centinelapistag.cardinalcommerce.com/V1/Cruise/Collect',
    'cardinalCollectionFormOrigin' => 'https://centinelapistag.cardinalcommerce.com',
    'cardinalStepUpURL'            => 'https://centinelapistag.cardinalcommerce.com/V2/Cruise/StepUp',
    // ---------- //
    'deviceDataCollectionURL_live'      => 'https://centinelapi.cardinalcommerce.com/V1/Cruise/Collect',
    'cardinalCollectionFormOrigin_live' => 'https://centinelapi.cardinalcommerce.com',
    'cardinalStepUpURL_live'            => 'https://centinelapi.cardinalcommerce.com/V2/Cruise/StepUp'
];

?>
