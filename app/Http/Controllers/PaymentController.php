<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Reservation;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function payFees($patient_id,$reservation_id)
    {
        // $patient_id = auth()->guard('patient')->user()->id;
        // $reservation = Reservation::find($reservation_id);
        // if(!$reservation)
        // {
        //     return "Not reserved";
        // }
        $ipPostFields = ['InvoiceAmount' => 100, 'CurrencyIso' => 'KWD'];
        $paymentMethods = $this->InitiateSession(env("MYFATOORAH_URL"), env("MYFATOORAH_TOKEN"), $ipPostFields);
        return
        [
            "countryCode"=>$paymentMethods->CountryCode,
            "sessionId" => $paymentMethods->SessionId
        ];
        return view("paymentForm",["data"=>$paymentMethods->CountryCode,"data2"=>$paymentMethods->SessionId]);
        $paymentMethodId = 2;
        // $postFields = [
        //     //Fill required data
        //     'DisplayCurrencyIso' => 'EGP',
        //     'NotificationOption' => 'Lnk', //'SMS', 'EML', or 'ALL'
        //     'InvoiceValue'       => $reservation->appointment->doctor->fees,
        //     'CustomerName'       => "DR/".$reservation->appointment->doctor->user->fname.' '.$reservation->appointment->doctor->user->lname,
        //     'CallBackUrl'        => 'https://example.com/callback.php',
        //     'ErrorUrl'           => 'https://example.com/callback.php'
        // ];
        $postFields = [
            //Fill required data
            'paymentMethodId' => $paymentMethodId,
            'SessionId'       => $paymentMethods->SessionId,
            'InvoiceValue'    => '50',
            'CallBackUrl'     => 'https://example.com/callback.php',
            'ErrorUrl'        => 'https://example.com/callback.php'
        ];
        
        $data = $this->executePayment(env("MYFATOORAH_URL"), env("MYFATOORAH_TOKEN"), $postFields);

        $paymentLink = $data->PaymentURL;
        // $data = $this->sendPayment(env("MYFATOORAH_URL"), env("MYFATOORAH_TOKEN"), $postFields);
        // $invoiceId   = $data->InvoiceId;
        // $paymentLink = $data->InvoiceURL;
        // $invoice = new Invoice;
        // $invoice->invoiceID = $invoiceId;
        // $invoice->patient_id = $patient_id;
        // $invoice->reservation_id = $reservation_id;
        // $invoice->save();
        return $paymentLink;
    }
    public function executePaymentgetway($id,$reservation_id,$sessionID)
    {
        $patient_id = auth()->guard('patient')->user()->id;
        $patient_id = 5;
        $reservation = Reservation::find($reservation_id);
        if(!$reservation)
        {
            return "Not reserved";
        }

        $postFields = [
            'SessionId'       => $sessionID,
            'InvoiceValue'    => $reservation->appointment->doctor->fees,
            'CallBackUrl'     => 'https://a4-health.herokuapp.com/api/patients/'.$patient_id.'/reservations/'.$reservation->id.'/pay/done',
            'ErrorUrl'        => 'https://example.com/callback.php',
        ];
        $data = $this->executePayment(env("MYFATOORAH_URL"), env("MYFATOORAH_TOKEN"), $postFields);
        return
        [
            'url' =>$data->PaymentURL
        ];
    }
    function InitiateSession($apiURL, $apiKey, $postFields) {

        $json = $this->callAPI("$apiURL/v2/InitiateSession", $apiKey, $postFields);
        return $json->Data;
    }
    function executePayment($apiURL, $apiKey, $postFields) {

        $json = $this->callAPI("$apiURL/v2/ExecutePayment", $apiKey, $postFields);
        return $json->Data;
    }
    function callAPI($endpointURL, $apiKey, $postFields = [], $requestType = 'POST') {

        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST  => $requestType,
            CURLOPT_POSTFIELDS     => json_encode($postFields),
            CURLOPT_HTTPHEADER     => array("Authorization: Bearer $apiKey", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));
    
        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);
    
        curl_close($curl);
    
        if ($curlErr) {
            //Curl is not working in your server
            die("Curl Error: $curlErr");
        }
    
        $error = $this->handleError($response);
        if ($error) {
            die("Error: $error");
        }
    
        return json_decode($response);
    }
    function handleError($response) {

        $json = json_decode($response);
        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return null;
        }
    
        //Check for the errors
        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');
    
            $error = implode(', ', array_map(function ($k, $v) {
                        return "$k: $v";
                    }, array_keys($blogDatas), array_values($blogDatas)));
        } else if (isset($json->Data->ErrorMessage)) {
            $error = $json->Data->ErrorMessage;
        }
    
        if (empty($error)) {
            $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
        }
    
        return $error;
    }
    public function changeStatus($id,$reservation_id)
    {
        $reservation = Reservation::find($reservation_id);
        $reservation->payment_status = "paid";
        $reservation->save();
        return Redirect::to('https://a4-health-a.herokuapp.com/');
    }
}

