<?php

namespace App\Provider;


class BaseProvider
{

    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;
    private $scope;


    public function __construct($client_id, $client_secret, $redirect_uri, $scope)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->scope = $scope;
    }


    public function getAuthorizationUrl(): string
    {
        return (http_build_query([
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'scope' => $this->scope,
            'response_type' => 'code'
        ]));
    }

    public function sendRequest($url , $method = 'GET', $data = null, $headers = null) : array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);

        curl_close($ch);
        //var_dump($result);
        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }

}