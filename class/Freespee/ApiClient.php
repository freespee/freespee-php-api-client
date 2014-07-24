<?php
namespace Freespee;

class ApiClient
{
    protected $username;
    protected $password;
    protected $baseUrl;

    public function setUsername($s)
    {
        $this->username = $s;
    }

    public function setPassword($s)
    {
        $this->password = $s;
    }

    public function setBaseUrl($s)
    {
        $this->baseUrl = $s;
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

    public function getRequest($resource)
    {
        if (!$this->baseUrl) {
            throw new Exception('requires baseUrl');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$resource);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);

        if ($this->username && $this->password) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        }

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $res = new ApiResponse();
        $res->httpCode = $httpCode;
        $res->result = json_decode($output, true);
        return $res;
    }

    public function postRequest($resource, $data)
    {
        if (!$this->baseUrl) {
            throw new Exception('requires baseUrl');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$resource);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        if ($this->username && $this->password) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        }

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $res = new ApiResponse();
        $res->httpCode = $httpCode;
        $res->result = json_decode($output, true);
        return $res;
    }

    public function putRequest($resource, $data)
    {
        if (!$this->baseUrl) {
            throw new Exception('requires baseUrl');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$resource);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        if ($this->username && $this->password) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        }

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $res = new ApiResponse();
        $res->httpCode = $httpCode;
        $res->result = json_decode($output, true);
        return $res;
    }

    public function deleteRequest($resource, $data)
    {
        if (!$this->baseUrl) {
            throw new Exception('requires baseUrl');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$resource);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        if ($this->username && $this->password) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        }

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $res = new ApiResponse();
        $res->httpCode = $httpCode;
        $res->result = json_decode($output, true);
        return $res;
    }
}
