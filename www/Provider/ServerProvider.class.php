<?php

namespace App\Provider;


class ServerProvider extends BaseProvider
{

    private string $url = 'http://localhost:8080/oauth2/';
    private string $token = '';
    private array $headers = [];


    public function __construct($client_id, $client_secret, $redirect_uri, $scope)
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $scope);
    }


    public function getAuthorizationUrl(): string
    {
        return $this->url . 'authorize?' . parent::getAuthorizationUrl();
    }

    public function getToken(string $code): string|null
    {
        $data = [
            'code' => $code,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirect_uri,
        ];
        $response = $this->sendRequest('token', 'POST', $data);

        $this->token = $response['access_token'] ?? null;

        return $this->token;
    }


    public function getUserInfo($token)
    {
        return [
            'id' => 1,
            'login' => 'Doe',
            'discriminator' =>  null,
            'avatar' =>  null,
        ];
    }

    public function sendRequest($uri, $method = 'GET', $data = null, $headers = []): array
    {
        $url = $this->url . $uri;
        return parent::sendRequest($url, $method, $data, $headers);
    }


}