<?php


namespace App\Controller;

use App\Core\Session;
use App\Provider\TwitchProvider;

class Twitch extends BaseController
{

private TwitchProvider $twitchProvider;
    public function loginWithTwitch(): void
    {
        $twitchProvider = new TwitchProvider(
            TWITCH_CLIENT_ID,
            TWITCH_CLIENT_SECRET,
            TWITCH_REDIRECT_URI,
            TWITCH_SCOPE
        );

        header("Location: " . $twitchProvider->getAuthorizationUrl());
    }

    public function twitchOauthSuccess()
    {
        $twitchProvider = new TwitchProvider(
            TWITCH_CLIENT_ID,
            TWITCH_CLIENT_SECRET,
            TWITCH_REDIRECT_URI,
            TWITCH_SCOPE
        );

        $code = $this->request->get('code');
        if ($code) {
            $token = $twitchProvider->getToken($code);
            if ($token) {
                $session = Session::getInstance();
                $session->set('provider', 'twitch');
                $session->set('token', $token);
                header("Location: /callback");
            }
        } else {
            die("No code");
        }

    }


    /*
     * "client_id": "lmswv8uznrej5klmxb0slsuert4gqk",
    "login": "ossama9_",
    "scopes": [
    "channel:manage:polls",
    "channel:read:polls"
    ],
    "user_id": "806661445",
    "expires_in": 15186
    */
}
