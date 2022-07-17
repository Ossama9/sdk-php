<?php


namespace App\Controller;


use App\Core\Session;
use App\Core\View;
use App\Model\App;
use App\Model\Code;
use App\Model\Token;
use App\Provider\ServerProvider;

class Server extends BaseController
{


    public function loginWithServer()
    {
        $serverProvider = new ServerProvider(
            SERVER_CLIENT_ID,
            SERVER_CLIENT_SECRET,
            SERVER_REDIRECT_URI,
            SERVER_SCOPE,
        );

        header("Location: " . $serverProvider->getAuthorizationUrl());

    }


    public function serverOauthSuccess()
    {
        $code = $this->request->get('code');
        $serverProvider = new ServerProvider(
            SERVER_CLIENT_ID,
            SERVER_CLIENT_SECRET,
            SERVER_REDIRECT_URI,
            SERVER_SCOPE,
        );

        if ($code) {
            $params = http_build_query([
                'code' => $code,
                'client_id' => SERVER_CLIENT_ID,
                'client_secret' => SERVER_CLIENT_SECRET,
                'grant_type' => 'authorization_code',
                'redirect_uri' => SERVER_REDIRECT_URI,
            ]);

            header("Location: /oauth2/token?" . $params);
        }
    }


    public function register()
    {
        if ($_POST) {
            $nameApp = $_POST['nameApp'] ?? null;
            $url = $_POST['url'] ?? null;
            $redirecturi = $_POST['redirecturi'] ?? null;

            if ($nameApp && $url && $redirecturi) {
                $app = new App();
                $app->setName($nameApp);
                $app->setUrl($url);
                $app->setRedirectUri($redirecturi);
                $app->save();
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";


                echo "Client id = " . $app->getClientId();
                echo "<br>";
                echo "Client secret = " . $app->getClientSecret();
            }
        } else {
            $view = new View("register");
        }
    }


    public function authorize()
    {


        $app = new App();
        $app->setClientId($_GET['client_id']);
        $app->setRedirectUri($_GET['redirect_uri']);
        $app = $app->findOne();

        if ($app) {
            $view = new View("authorize");
            $view->assign("app", $app);
        } else {
            die("app not found");
        }

    }


    public function authSuccess()
    {
        $app = new App();
        $app->setClientId($_GET['client_id']);
        $result = $app->findOne();

        if ($result) {

            $code = new Code();
            $code->setClientId($_GET['client_id']);
            $code->setUserId(1);
            $code->setExpiresAt(time() + 3600);
            $code->generateCode();
            $code->save();

            header("Location: " . $result['redirect_uri'] . "?code=" . $code->getCode());

        } else {
            die("app not found");
        }
    }


    public function token()
    {


        ['client_id' => $clientId, 'client_secret' => $clientSecret, 'grant_type' => $grantType, 'redirect_uri' => $redirect, 'code' => $code] = $_GET;
        try {
            $app = new App();
            $app = $app->findOne(['client_id' => $clientId, 'client_secret' => $clientSecret, 'redirect_uri' => $redirect]);


            $token = new Token();
            $token->setClientId($clientId);
            $token->setUserId(1);
            $token->setExpiresAt(time() + 3600);
            $token->generateToken();
            $token->save();

            $session = Session::getInstance();
            $session->set('token', $token->getAccessToken());
            $session->set('provider', "server");
            header("location: /callback");
            if (!$app) {
                throw new \InvalidArgumentException(401);
            }

        } catch (\UnhandledMatchError $e) {
            http_response_code(400);
        } catch (\InvalidArgumentException $e) {
            http_response_code(intval($e->getMessage()));
        }

    }


    public function me()
    {
        echo "me";
    }

    public function stats()
    {
        echo "stats";
    }
}