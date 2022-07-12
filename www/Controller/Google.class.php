<?php

namespace App\Controller;

use App\Core\Session;
use App\Provider\GoogleProvider;

class Google extends BaseController
{
    public function loginWithGoogle()
    {
        $googleProvider = new GoogleProvider(
            GOOGLE_CLIENT_ID,
            GOOGLE_CLIENT_SECRET,
            GOOGLE_REDIRECT_URI,
            GOOGLE_SCOPE,
        );

        header("Location: " . $googleProvider->getAuthorizationUrl());

    }

    public function googleOauthSuccess()
    {
        $googleProvider = new GoogleProvider(
            GOOGLE_CLIENT_ID,
            GOOGLE_CLIENT_SECRET,
            GOOGLE_REDIRECT_URI,
            GOOGLE_SCOPE,
        );

        $code  = $this->request->get('code');
        if ($code){
            $token = $googleProvider->getToken($code);
            if ($token) {
                $session = Session::getInstance();
                $session->set('provider', 'google');
                $session->set('token', $token);
                header("Location: /callback");
            }
        }
    }
}