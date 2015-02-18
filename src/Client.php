<?php namespace Freespee\ApiClient;

class Client
{
    protected $username;
    protected $password;
    protected $baseUrl;

    public function __construct(array $config = [])
    {
        if (array_key_exists('base_url', $config)) {
            $this->setBaseUrl($config['base_url']);
        }
        if (array_key_exists('username', $config)) {
            $this->setUsername($config['username']);
        }
        if (array_key_exists('password', $config)) {
            $this->setPassword($config['password']);
        }
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    private function createCurlObject($method, $resource, $data = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$resource);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        if ($this->username && $this->password) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        }

        if ($method != 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        return $ch;
    }

    private function buildResponse($ch)
    {
        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $res = new Response();
        $res->httpCode = $httpCode;
        $res->result = json_decode($output, true);

        return $res;
    }

    /**
     * @param $resource
     * @return Response
     */
    public function getRequest($resource)
    {
        return $this->buildResponse(
            $this->createCurlObject('GET', $resource)
        );
    }

    /**
     * @param $resource
     * @param $data
     * @return Response
     */
    public function postRequest($resource, $data)
    {
        return $this->buildResponse(
            $this->createCurlObject('POST', $resource, $data)
        );
    }

    /**
     * @param $resource
     * @param $data
     * @return Response
     */
    public function putRequest($resource, $data)
    {
        return $this->buildResponse(
            $this->createCurlObject('PUT', $resource, $data)
        );
    }

    /**
     * @param $resource
     * @param $data
     * @return Response
     */
    public function deleteRequest($resource, $data)
    {
        return $this->buildResponse(
            $this->createCurlObject('DELETE', $resource, $data)
        );
    }
}
