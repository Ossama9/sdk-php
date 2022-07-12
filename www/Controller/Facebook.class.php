<?php

namespace App\Controller;

use App\Core\Session;
use App\Provider\FacebookProvider;

class Facebook extends BaseController
{
    public function loginWithFacebook()
    {
        $discordProvider = new DiscordProvider(
            DISCORD_CLIENT_ID,
            DISCORD_CLIENT_SECRET,
            DISCORD_REDIRECT_URI,
            DISCORD_SCOPE,
        );

        header("Location: " . $discordProvider->getAuthorizationUrl());

    }
}