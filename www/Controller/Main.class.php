<?php

namespace App\Controller;

use App\Core\View;
use App\Controller\BaseController;
use App\Core\Session;
use App\Model\User as UserModel;
use App\Provider\TwitchProvider;

class Main extends BaseController
{

    public function home()
    {
        $view = new View("home", "front");
    }


    public function callback()
    {
        $session = Session::getInstance();
        $token = $session->get("token");
        $providerName = $session->get("provider");
        $providerName = ucfirst($providerName);
        $provider = strtoupper($providerName);


        $class = "App\\Provider\\" . ucfirst($provider) . "Provider";

        if (class_exists($class)) {
            $provider = new $class(
                constant($provider . "_CLIENT_ID"),
                constant($provider . "_CLIENT_SECRET"),
                constant($provider . "_REDIRECT_URI"),
                constant($provider . "_SCOPE")
            );
            $response = $provider->getUserInfo($token);
            $login = $response['login'] ?? null;
            if ($login){
                echo "Vous êtes connecté avec ".$providerName;
                $session->set("login", $login);
                $session->addFlashMessage("success","Bonjour ".$login." vous êtes connecté avec ".$providerName);
                header("Location: /");
            }
        }
        die();
    }


}