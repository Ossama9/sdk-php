<?php

define("FB_CLIENT_ID", '388582336578333');
define("FB_CLIENT_SECRET", '0b3956e742ace3297c030209807a43a4');

// Create a login page with a link to oauth
function login()
{
    $fbQueryParams = http_build_query([
        "state"=>bin2hex(random_bytes(16)),
        "client_id"=> FB_CLIENT_ID,
        "scope"=>"public_profile,email",
        "redirect_uri"=>"https://localhost:8081/fb_oauth_success",
    ]);
    echo "<a href=\"https://www.facebook.com/v14.0/dialog/oauth?{$fbQueryParams}\">Login with Facebook</a>";
}

// Facebook oauth: exchange code with token then get user info
function fbcallback()
{
    $token = getToken("https://graph.facebook.com/v14.0/oauth/access_token", FB_CLIENT_ID, FB_CLIENT_SECRET);
    $user = getFbUser($token);
    $unifiedUser = (fn () => [
        "id" => $user["id"],
        "name" => $user["name"],
        "email" => $user["email"],
        "firstName" => $user['first_name'],
        "lastName" => $user['last_name'],
    ])();
    var_dump($unifiedUser);
}
function getFbUser($token)
{
    $context = stream_context_create([
        "http"=>[
            "header"=>"Authorization: Bearer {$token}"
        ]
    ]);
    $response = file_get_contents("https://graph.facebook.com/v14.0/me?fields=last_name,first_name,email", false, $context);
    if (!$response) {
        echo $http_response_header;
        return;
    }
    return json_decode($response, true);
}
function getToken($baseUrl, $clientId, $clientSecret)
{
    ["code"=> $code, "state" => $state] = $_GET;
    $queryParams = http_build_query([
        "client_id"=> $clientId,
        "client_secret"=> $clientSecret,
        "redirect_uri"=>"https://localhost/fb_oauth_success",
        "code"=> $code,
        "grant_type"=>"authorization_code",
    ]);

    $url = $baseUrl . "?{$queryParams}";
    $response = file_get_contents($url);
    if (!$response) {
        echo $http_response_header;
        return;
    }
    ["access_token" => $token] = json_decode($response, true);

    return $token;
}

$route = $_SERVER["REQUEST_URI"];
switch (strtok($route, "?")) {
    case '/login':
        login();
        break;
    case '/oauth_success':
        callback();
        break;
    case '/fb_oauth_success':
        fbcallback();
        break;
    default:
        http_response_code(404);
}
