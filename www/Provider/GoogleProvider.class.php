<?php

namespace App\Provider;

class GoogleProvider extends BaseProvider
{
    private string $url = 'https://accounts.google.com/o/oauth2/';
    private string $urlToken = 'https://oauth2.googleapis.com/';
    private string $token = '';

    public function __construct($client_id, $client_secret, $redirect_uri, $scope)
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $scope);
    }

    public function getAuthorizationUrl(): string
    {
        return $this->url . 'v2/auth?' . parent::getAuthorizationUrl();
    }

    public function sendRequest($uri, $method = 'GET', $data = null, $headers = []): array
    {
        $url = $this->urlToken . $uri;
        return parent::sendRequest($url, $method, $data, $headers);
    }

    public function getToken(string $code) : string
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


    public function getUserInfo(string $token) : array
    {
        $headers = [
            'Authorization: Bearer ' . $token
        ];
        $response = parent::sendRequest('https://www.googleapis.com/oauth2/v2/userinfo', 'GET', null, $headers);
        return [
            'id' => $response['id'] ?? null,
            'login' => $response['email'] ?? null,
            'discriminator' => $response['email'] ?? null,
            'avatar' => $response['picture'] ?? null,
        ];
    }
}


