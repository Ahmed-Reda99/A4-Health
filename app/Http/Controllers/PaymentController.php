<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
//     private $base_url;
//     private $Client;
//     private $Token;

//     public function __construct(Client $request_client)
//     {
//         $this->Client = $request_client;
//         $this->base_url = env("MYFATOORAH_URL");
//         $this->Token = env("MYFATOORAH_TOKEN");
//     }


// //Fill POST fields array
// $ipPostFields = ['InvoiceAmount' => 100, 'CurrencyIso' => 'KWD'];

// //Call endpoint
// $paymentMethods = initiatePayment($apiURL, $apiKey, $ipPostFields);

// $paymentMethodId = 20;


// //Fill POST fields array
// $postFields = [
//     //Fill required data
//     'paymentMethodId' => $paymentMethodId,
//     'InvoiceValue'    => '50',
//     'CallBackUrl'     => 'https://example.com/callback.php',
//     'ErrorUrl'        => 'https://example.com/callback.php',
// ];

// //Call endpoint
// $data = executePayment($apiURL, $apiKey, $postFields);

// $invoiceId  = $data->InvoiceId;
// $paymentURL = $data->PaymentURL;


// /* ------------------------ Call DirectPayment Endpoint --------------------- */
// //Fill POST fields array
// $cardInfo = [
//     'PaymentType' => 'card',
//     'Bypass3DS'   => false,
//     'Card'        => [
//         'Number'         => '5123450000000008',
//         'ExpiryMonth'    => '05',
//         'ExpiryYear'     => '21',
//         'SecurityCode'   => '100',
//         'CardHolderName' => 'fname lname'
//     ]
// ];

// //Call endpoint
// $directData = directPayment($paymentURL, $apiKey, $cardInfo);

// //You can save payment data in database as per your needs
// $paymentId   = $directData->PaymentId;
// $paymentLink = $directData->PaymentURL;

// //Redirect your customer to the OTP page to complete the payment process
// //Display the payment link to your customer



// /* ------------------------ Functions --------------------------------------- */
// /*
//  * Initiate Payment Endpoint Function 
//  */

// function initiatePayment($apiURL, $apiKey, $postFields) {

//     $json = callAPI("$apiURL/v2/InitiatePayment", $apiKey, $postFields);
//     return $json->Data->PaymentMethods;
// }

// //------------------------------------------------------------------------------
// /*
//  * Execute Payment Endpoint Function 
//  */

// function executePayment($apiURL, $apiKey, $postFields) {

//     $json = callAPI("$apiURL/v2/ExecutePayment", $apiKey, $postFields);
//     return $json->Data;
// }

// //------------------------------------------------------------------------------
// /*
//  * Direct Payment Endpoint Function 
//  */

// function directPayment($paymentURL, $apiKey, $postFields) {

//     $json = callAPI($paymentURL, $apiKey, $postFields);
//     return $json->Data;
// }

// //------------------------------------------------------------------------------
// /*
//  * Call API Endpoint Function
//  */

// function callAPI($endpointURL, $apiKey, $postFields = [], $requestType = 'POST') {

//     $curl = curl_init($endpointURL);
//     curl_setopt_array($curl, array(
//         CURLOPT_CUSTOMREQUEST  => $requestType,
//         CURLOPT_POSTFIELDS     => json_encode($postFields),
//         CURLOPT_HTTPHEADER     => array("Authorization: Bearer $apiKey", 'Content-Type: application/json'),
//         CURLOPT_RETURNTRANSFER => true,
//     ));

//     $response = curl_exec($curl);
//     $curlErr  = curl_error($curl);

//     curl_close($curl);

//     if ($curlErr) {
//         //Curl is not working in your server
//         die("Curl Error: $curlErr");
//     }

//     $error = handleError($response);
//     if ($error) {
//         die("Error: $error");
//     }

//     return json_decode($response);
// }

// //------------------------------------------------------------------------------
// /*
//  * Handle Endpoint Errors Function 
//  */

// function handleError($response) {

//     $json = json_decode($response);
//     if (isset($json->IsSuccess) && $json->IsSuccess == true) {
//         return null;
//     }

//     //Check for the errors
//     if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
//         $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
//         $blogDatas = array_column($errorsObj, 'Error', 'Name');

//         $error = implode(', ', array_map(function ($k, $v) {
//                     return "$k: $v";
//                 }, array_keys($blogDatas), array_values($blogDatas)));
//     } else if (isset($json->Data->ErrorMessage)) {
//         $error = $json->Data->ErrorMessage;
//     }

//     if (empty($error)) {
//         $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
//     }

//     return $error;
// }

/* -------------------------------------------------------------------------- */
}
