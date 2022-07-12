<?php


namespace App\Controller;

use App\Core\Session;
use App\Provider\DiscordProvider;

class Discord extends BaseController
{


    public function loginWithDiscord()
    {
        $discordProvider = new DiscordProvider(
            DISCORD_CLIENT_ID,
            DISCORD_CLIENT_SECRET,
            DISCORD_REDIRECT_URI,
            DISCORD_SCOPE,
        );

        header("Location: " . $discordProvider->getAuthorizationUrl());

    }


    public function discordOauthSuccess()
    {
        $discordProvider = new DiscordProvider(
            DISCORD_CLIENT_ID,
            DISCORD_CLIENT_SECRET,
            DISCORD_REDIRECT_URI,
            DISCORD_SCOPE,
        );

        $code  = $this->request->get('code');
        if ($code){
            $token = $discordProvider->getToken($code);

            if ($token) {
                $session = Session::getInstance();
                $session->set('provider', 'discord');
                $session->set('token', $token);
                header("Location: /callback");
            }
        }
    }





}