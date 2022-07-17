<?php

namespace App\Controller;

use App\Core\Session;
use App\Provider\FacebookProvider;

class Facebook extends BaseController
{
    public function loginWithFacebook()
    {
        $facebookProvider = new FacebookProvider(
            FACEBOOK_CLIENT_ID,
            FACEBOOK_CLIENT_SECRET,
            FACEBOOK_REDIRECT_URI,
            FACEBOOK_SCOPE,
        );

        header("Location: " . $facebookProvider->getAuthorizationUrl());

    }

    public function facebookOauthSuccess()
    {
        $facebookProvider = new FacebookProvider(
            FACEBOOK_CLIENT_ID,
            FACEBOOK_CLIENT_SECRET,
            FACEBOOK_REDIRECT_URI,
            FACEBOOK_SCOPE,
        );

        $code  = $this->request->get('code');
        if ($code){
            $token = $facebookProvider->getToken($code);
            if ($token) {
                $session = Session::getInstance();
                $session->set('provider', 'facebook');
                $session->set('token', $token);
                header("Location: /callback");
            }
        }
    }
}