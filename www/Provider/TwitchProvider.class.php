<?php


namespace App\Provider;

class TwitchProvider extends BaseProvider
{

    private string $url = 'https://id.twitch.tv/oauth2/';
    private string $token = '';

    public function __construct($client_id, $client_secret, $redirect_uri, $scope)
    {
        parent::__construct($client_id, $client_secret, $redirect_uri, $scope);
    }


    public function getCode()
    {

    }


    public function getAuthorizationUrl(): string
    {
        parent::getAuthorizationUrl();
        return $this->url . 'authorize?' . parent::getAuthorizationUrl();
    }

    public function sendRequest($uri, $method = 'GET', $data = null, $headers = []): array
    {
        $url = $this->url . $uri;
        return parent::sendRequest($url, $method, $data, $headers);
    }

    public function getToken(string $code): ?string
    {
        $data = [
            'client_secret' => $this->client_secret,
            'client_id' => $this->client_id,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirect_uri,
            'code' => $code
        ];
        $response = $this->sendRequest('token', 'POST', $data);
        $this->token = $response['access_token'] ?? null;
        return $this->token;
    }

    public function getUserInfo(string $token): ?array
    {
        $headers = [
            'Authorization: OAuth ' . $token
        ];
        $response =  $this->sendRequest('validate', 'GET', null, $headers);
        return [
            'id' => $response['id'] ?? null,
            'login' => $response['login'] ?? null,
            'discriminator' => $response['discriminator'] ?? null,
            'avatar' => $response['avatar'] ?? null,
        ];
    }

}