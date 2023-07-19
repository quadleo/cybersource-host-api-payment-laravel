Below is a template for creating README documentation for integrating both Cybersource Hosted Checkout and API Checkout payment methods into your web application. You should customize this template based on your specific integration requirements and add relevant details as needed.

---
# Cybersource Payment Integration Documentation

This README provides guidelines for integrating Cybersource payment solutions into your web application. Cybersource offers two main payment methods: Hosted Checkout and API Checkout. Choose the method that best suits your requirements and follow the instructions below for smooth payment integration.

## Table of Contents
1. [Introduction](#introduction)
2. [Prerequisites](#prerequisites)
3. [Hosted Checkout](#hosted-checkout)
   - [Installation](#installation)
   - [Configuration](#configuration)
   - [API Reference](#api-reference)
   - [Response Handling](#response-handling)
   - [Security Considerations](#security-considerations)
   - [Examples](#examples)
   - [Troubleshooting](#troubleshooting)
   - [Frequently Asked Questions](#frequently-asked-questions)
4. [API Checkout](#api-checkout)
   - [Installation](#installation-1)
   - [Authentication](#authentication)
   - [API Reference](#api-reference-1)
   - [Integration Guide](#integration-guide)
   - [Error Handling](#error-handling)
   - [Security and Compliance](#security-and-compliance)
   - [Code Examples](#code-examples)
   - [Support](#support)

## 1. Introduction

Cybersource provides robust and secure payment solutions for processing online transactions. Whether you choose Hosted Checkout or API Checkout, Cybersource ensures a seamless payment experience for your customers while maintaining the highest level of security.

## 2. Prerequisites

Before proceeding with the integration, ensure you have the following:

- Cybersource merchant account credentials
- A web server or hosting environment
- Appropriate access rights to make API calls if using API Checkout
- Knowledge of programming languages and frameworks you plan to use (e.g., JavaScript, PHP, Python, etc.)

## 3. Hosted Checkout

Cybersource Hosted Checkout redirects your customers to a secure payment page hosted by Cybersource. After payment processing, the customer is redirected back to your website. Follow the steps below for integration.

### Installation

1. Obtain your Cybersource account credentials and merchant ID.
2. Configure your Cybersource account to enable Hosted Checkout.
3. Download the Hosted Checkout SDK or use the provided JavaScript integration code.

### Configuration

- Customize the appearance and branding of the payment page.
- Set up the success and failure callback URLs to handle payment responses.

### API Reference

- Refer to the Hosted Checkout API documentation for available API endpoints and request parameters.

### Response Handling

- Implement code to handle the response data received from Cybersource after payment processing.

### Security Considerations

- Follow Cybersource's security guidelines to protect sensitive payment information.

### Examples

- Find code examples in various programming languages to help with the integration.

### Troubleshooting

- Common issues and solutions during the integration process.

### Frequently Asked Questions

- Answers to common questions developers may have about Hosted Checkout.

## 4. API Checkout

Cybersource API Checkout allows you to have more control over the payment form and user experience. You directly make API calls to Cybersource for payment processing. Follow the steps below for integration.

### Installation

1. Obtain your Cybersource account credentials and merchant ID.
2. Set up API credentials for authentication.
3. Download the Cybersource API client library or use your preferred HTTP client.

### Authentication

- Learn how to authenticate API calls to Cybersource securely.

### API Reference

- Refer to the API Checkout documentation for available API endpoints, request parameters, and response payloads.

### Integration Guide

- Guidelines on designing and implementing the payment flow using API Checkout.

### Error Handling

- Understand how to handle errors and exceptions returned by the API.

### Security and Compliance

- Follow best practices for securing sensitive data and meeting compliance standards.

### Code Examples

- Sample code snippets demonstrating how to make API calls for payment processing.

### Support

- Contact information for support channels in case you encounter issues during the integration process.

---
Please note that this is a generic template, and you should supplement it with specific details and instructions provided by Cybersource's official documentation. Always refer to the latest documentation and guidelines from Cybersource to ensure accurate and up-to-date integration.

### reference 
---
 https://github.com/e-payment/test-3ds2-api

 ### Hosted Checkout Example (JavaScript)
```
<!-- Your payment form in HTML -->
<form action="https://secureacceptance.cybersource.com/pay" method="POST" id="cybersource-payment-form">
    <!-- Add necessary input fields here (e.g., amount, currency, merchant ID, etc.) -->
    <input type="hidden" name="access_key" value="YOUR_ACCESS_KEY">
    <input type="hidden" name="profile_id" value="YOUR_PROFILE_ID">
    <!-- Add other required fields -->

    <input type="submit" value="Pay Now">
</form>

<!-- JavaScript to submit the form on page load -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("cybersource-payment-form").submit();
    });
</script>

```
### API Checkout Example (Using PHP Requests library):
## Process for payment with Card Number
- Step1: enter card number.
- Step2: enter expiry month and year, after year field call api , 
  that will call api for auth-setup to validate your card details.
	And you'll get the success or error response.
- Step3: If success, proceed for payment, and call for authentication api that will authenticate your details, 
	 If authorized, it will redirect to opt section.
	 If failed, please redirect or show the error message from response.
- Step4: After otp validation, it will redirect to your response page where you have to capture the response that included authtransationId.
- Step5: Lastly, call makepayment api for settlement with that authtransactionID and redirect to success page. That's all.
```
<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://apitest.cybersource.com/risk/v1/authentications/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
	"clientReferenceInformation": {
		"code": "cybs_visa_3"
	},
	"orderInformation": {
		"amountDetails": {
			"currency": "USD",
			"totalAmount": "100"
		},
		"billTo": {
			"address1": "901 metro center blvd",
			"address2": "metro 3",
			"administrativeArea": "CA",
			"country": "US",
			"locality": "san francisco",
			"firstName": "John",
			"lastName": "Doe",
			"phoneNumber": "18007097779",
			"postalCode": "94404",
			"email": "email@email.com"
		}
	},
	"paymentInformation": {
		"card": {
			"type": "001",
			"expirationMonth": "12",
			"expirationYear": "2027",
			"number": "4456530000001096"
		}
	},
	"buyerInformation": {
		"mobilePhone": "1245789632"
	},
	"consumerAuthenticationInformation": {
		"referenceId": "222132d0-f2d1-4971-ba2f-6b444d9ee438",
		"transactionMode": "S",
		"returnUrl": "https://cybs-api.ngrok.io/response.php"
	}
}',
  CURLOPT_HTTPHEADER => array(
    'v-c-merchant-id: kr950210047',
    'Date: Wed, 28 Sep 2022 04:36:05 GMT',
    'Host: apitest.cybersource.com',
    'Digest: SHA-256=JJ2EiIvDnAZKnQcgKivhmI4B0r71xo67bimnUpG+IOM=',
    'Signature: keyid="f9a2e793-d5f4-450a-bc51-e3053e0f08e8", algorithm="HmacSHA256", headers="host date (request-target) digest v-c-merchant-id", signature="DzHMpVbVte01t9pdEl7BqKAe5GOybxaW4SmuueorNM4="',
    'Content-Type: application/json',
    'User-Agent: Mozilla/5.0'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
```

```