<?php
namespace App\Http\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class FatooraService
{
    private $base_url;
    private $headers;
    private $request_client;

    public function __construct(Client $request_client)
    {
        $this->request_client = $request_client;
        $this->base_url = env("MYFATOORAH_URL");
        $this->headers = 
        [
            'Content-Type' => 'application/json',
            'authorization' => 'Bearer '.env("MYFATOORAH_TOKEN")
        ];
        
    }
    private function buildRequest($url,$method,$body = [])
    {
        $request = new Request($method,$this->base_url.$url,$this->headers);
        if(!$body)
        {
            return false;
        }
        $response = $this->request_client->send($request,[
            'json'=>$body
        ]);
        if($response->getStatusCode() != 200)
        {
            return false;
        }
        $response = json_decode($response->getBody(),true);
        return $response;
    }

}
?>