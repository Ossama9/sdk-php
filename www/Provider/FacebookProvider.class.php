<?php

namespace App\Provider;

class FacebookProvider extends BaseProvider
{
    private string $url = 'https://www.facebook.com/v14.0/dialog/oauth?';
    private string $urlToken = 'https://graph.facebook.com/v14.0/oauth/';
    private string $token = '';

    public function __construct($client_id, $client_secret, $redirect_uri, $scope)
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $scope);
    }

    public function getAuthorizationUrl(): string
    {
        return $this->url . '' . parent::getAuthorizationUrl();
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
        $response = $this->sendRequest('access_token', 'POST', $data);
        $this->token = $response['access_token'] ?? null;
        return $this->token;
    }


    public function getUserInfo(string $token) : array
    {
        $headers = [
            'Authorization: Bearer ' . $token
        ];
        $response = parent::sendRequest('https://graph.facebook.com/v14.0/me?fields=last_name,first_name,email', 'GET', null, $headers);
        return [
            'id' => $response['email'] ?? null,
            'login' => $response['last_name'] ?? null,
            'discriminator' => $response['first_name'] ?? null,
            'avatar' => $response['avatar'] ?? null,
        ];
    }



}