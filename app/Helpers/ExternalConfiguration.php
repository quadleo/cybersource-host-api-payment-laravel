<?php
/*
* Purpose : passing Authentication config object to the configuration
*/
namespace App\Helpers;

use CyberSource\Authentication\Core\MerchantConfiguration;
use CyberSource\Configuration;
use CyberSource\Logging\LogConfiguration;

class ExternalConfiguration
{
        private $merchantConfig;
        private $intermediateMerchantConfig;
        protected $authType = "http_signature"; //http_signature/jwt
        protected $enableLog = false;
        protected $logSize = "1048576";
        protected $logFile = "Log";
        protected $logFilename = "Cybs.log";
        protected $merchantID = null;
        protected $apiKeyID = null;
        protected $secretKey = null;
        protected $keyAlias = null;
        protected $keyPass = null;
        protected $keyFilename = null;
        protected $keyDirectory = "Resources/";
        protected $runEnv = null;

        // MetaKey configuration [Start]
        protected $useMetaKey = false;
        protected $portfolioID = "";
        // MetaKey configuration [End]

        // new property has been added for user to configure the base path so that request can route the API calls via Azure Management URL.
        // Example: If intermediate url is https://manage.windowsazure.com then in property input can be same url or manage.windowsazure.com.
        protected $IntermediateHost = "https://manage.windowsazure.com";

        //OAuth related config
        protected $enableClientCert = false;
        protected $clientCertDirectory = "Resources/";
        protected $clientCertFile = "";
        protected $clientCertPassword = "";
        protected $clientId = "";
        protected $clientSecret = "";

        // New Logging
        protected $enableLogging = false;
        protected $debugLogFile = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "Log" . DIRECTORY_SEPARATOR . "debugTest.log";
        protected $errorLogFile = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "Log" . DIRECTORY_SEPARATOR . "errorTest.log";
        protected $logDateFormat = "Y-m-d\TH:i:s";
        protected $logFormat = "[%datetime%] [%level_name%] [%channel%] : %message%\n";
        protected $logMaxFiles = 3;
        protected $logLevel = "debug";
        protected $enableMasking = true;

        //initialize variable on constructor
        function __construct()
        {
                $this->merchantID = config('csservices.merchant_id');
                $this->apiKeyID = config('csservices.apikey_id');
                $this->secretKey = config('csservices.secret_key');
                $this->keyAlias = config('csservices.key_alias');
                $this->keyPass = config('csservices.key_pass');
                $this->keyFilename = config('csservices.key_file_name');
                $this->keyDirectory = "Resources/";
                $this->runEnv = config('csservices.run_env');
                $this->merchantConfigObject();
                $this->merchantConfigObjectForIntermediateHost();
        }
          //creating merchant config object
    function merchantConfigObject()
    {
        if (!isset($this->merchantConfig)) {
            $config = new MerchantConfiguration();
            $config->setauthenticationType(strtoupper(trim($this->authType)));
            $config->setMerchantID(trim($this->merchantID));
            $config->setApiKeyID($this->apiKeyID);
            $config->setSecretKey($this->secretKey);
            $config->setKeyFileName(trim($this->keyFilename));
            $config->setKeyAlias($this->keyAlias);
            $config->setKeyPassword($this->keyPass);
            $config->setUseMetaKey($this->useMetaKey);
            $config->setPortfolioID($this->portfolioID);
            $config->setKeysDirectory(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $this->keyDirectory);
            $config->setRunEnvironment($this->runEnv);

            // New Logging
            $logConfiguration = new LogConfiguration();
            $logConfiguration->enableLogging($this->enableLogging);
            $logConfiguration->setDebugLogFile($this->debugLogFile);
            $logConfiguration->setErrorLogFile($this->errorLogFile);
            $logConfiguration->setLogDateFormat($this->logDateFormat);
            $logConfiguration->setLogFormat($this->logFormat);
            $logConfiguration->setLogMaxFiles($this->logMaxFiles);
            $logConfiguration->setLogLevel($this->logLevel);
            $logConfiguration->enableMasking($this->enableMasking);
            $config->setLogConfiguration($logConfiguration);

            $config->validateMerchantData();
            $this->merchantConfig = $config;
        } else {
            return $this->merchantConfig;
        }
    }

    //creating merchant config for intermediate host object
    function merchantConfigObjectForIntermediateHost()
    {
        if (!isset($this->intermediateMerchantConfig)) {
            $config = new MerchantConfiguration();
            $config->setauthenticationType(strtoupper(trim($this->authType)));
            $config->setMerchantID(trim($this->merchantID));
            $config->setApiKeyID($this->apiKeyID);
            $config->setSecretKey($this->secretKey);
            $config->setKeyFileName(trim($this->keyFilename));
            $config->setKeyAlias($this->keyAlias);
            $config->setKeyPassword($this->keyPass);
            $config->setUseMetaKey($this->useMetaKey);
            $config->setPortfolioID($this->portfolioID);
            $config->setKeysDirectory(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $this->keyDirectory);
            $config->setRunEnvironment($this->runEnv);
            $config->setIntermediateHost($this->IntermediateHost);

            // New Logging
            $logConfiguration = new LogConfiguration();
            $logConfiguration->enableLogging($this->enableLogging);
            $logConfiguration->setDebugLogFile($this->debugLogFile);
            $logConfiguration->setErrorLogFile($this->errorLogFile);
            $logConfiguration->setLogDateFormat($this->logDateFormat);
            $logConfiguration->setLogFormat($this->logFormat);
            $logConfiguration->setLogMaxFiles($this->logMaxFiles);
            $logConfiguration->setLogLevel($this->logLevel);
            $logConfiguration->enableMasking($this->enableMasking);
            $config->setLogConfiguration($logConfiguration);

            $config->validateMerchantData();
            $this->intermediateMerchantConfig = $config;
        } else {
            return $this->intermediateMerchantConfig;
        }
    }


    function ConnectionHost()
    {
        $merchantConf = $this->merchantConfigObject();
        $config = new Configuration();
        $config->setHost($merchantConf->getHost());
        $config->setLogConfiguration($merchantConf->getLogConfiguration());
        return $config;
    }

    function ConnectionHostForIntermediateHost()
    {
        $intermediatemerchantConf = $this->merchantConfigObjectForIntermediateHost();
        $config = new Configuration();
        $config->setHost($intermediatemerchantConf->getHost());
        $config->setLogConfiguration($intermediatemerchantConf->getLogConfiguration());
        return $config;
    }

    function FutureDate($format){
        if($format){
            $rdate = date("Y-m-d",strtotime("+7 days"));
            $retDate = date($format,strtotime($rdate));
        }
        else{
            $retDate = date("Y-m",strtotime("+7 days"));
        }
        echo $retDate;
        return $retDate;
    }

    function CallTestLogging($testId, $apiName, $responseMessage){
        $runtime = date('d-m-Y H:i:s');
        $file = fopen("./CSV_Files/TestReport/TestResults.csv", "a+");
        fputcsv($file, array($testId, $runtime, $apiName, $responseMessage));
        fclose($file);
    }

    function downloadReport($downloadData, $fileName){
        $filePathName = __DIR__. DIRECTORY_SEPARATOR .$fileName;
        $file = fopen($filePathName, "w");
        fwrite($file, $downloadData);
        fclose($file);
        return __DIR__.'\\'.$fileName;
    }
}

?>