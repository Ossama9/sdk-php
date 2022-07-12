<?php


namespace App\Provider;


class DiscordProvider extends BaseProvider
{
    private string $url = 'https://discord.com/api/oauth2/';
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

    public function getToken(string $code)
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


    public function getUserInfo(string $token): array
    {
        $headers = [
            'Authorization: Bearer ' . $token
        ];
        $response = parent::sendRequest('https://discordapp.com/api/users/@me', 'GET', null, $headers);
        return [
            'id' => $response['id'] ?? null,
            'login' => $response['username'] ?? null,
            'discriminator' => $response['discriminator'] ?? null,
            'avatar' => $response['avatar'] ?? null,
        ];
    }


    public function sendRequest($uri, $method = 'GET', $data = null, $headers = []): array
    {
        $url = $this->url . $uri;
        return parent::sendRequest($url, $method, $data, $headers);
    }

}