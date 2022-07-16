<?php


namespace App\Model;

class App extends BaseModel
{
    protected $name;
    protected $url;
    protected $redirect_uri;
    protected $client_id;
    protected $client_secret;





    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }

    /**
     * @param mixed $redirect_uri
     */
    public function setRedirectUri($redirect_uri): void
    {
        $this->redirect_uri = $redirect_uri;
    }


    /**
     * @return mixed
     */
    public function getClientId()
    {

        return $this->client_id;
    }

    /**
     * @param mixed $client_id
     */
    public function setClientId($client_id): void
    {
        $this->client_id = $client_id;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->client_secret;
    }

    /**
     * @param mixed $client_secret
     */
    public function setClientSecret($client_secret): void
    {
        $this->client_secret = $client_secret;
    }


    public function save()
    {
        $this->client_id = $this->generateClientId();
        $this->client_secret = $this->generateClientSecret();
        parent::save();
    }








    private function generateClientId(): string
    {
        return md5(uniqid('', true));
    }

    private function generateClientSecret(): string
    {
        return md5(uniqid('', true));
    }


}