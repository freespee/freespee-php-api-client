<?php

class ApiClientTest extends \PHPUnit_Framework_TestCase
{
    private function getApiClient()
    {
        $cli = new \Freespee\ApiClient();
        $cli->setBaseUrl('https://api.analytics.freespee.com/2.4.9');

        $apiSettingsFile = realpath(__DIR__.'/..').'/settings.php';
        require $apiSettingsFile;

        return $cli;
    }

    function testPingUnauthorized()
    {
        $cli = $this->getApiClient();
        $cli->setUsername('');
        $cli->setPassword('');

        $expected = new \Freespee\ApiResponse();
        $expected->httpCode = 401; // Unauthorized
        $expected->result = null;

        $this->assertEquals(
            $expected,
            $cli->getRequest('/miscellaneous/ping')
        );
    }

    function testPing()
    {
        $cli = $this->getApiClient();

        $expected = new \Freespee\ApiResponse();
        $expected->httpCode = 200; // OK
        $expected->result = array('ping' => 'ok');

        $this->assertEquals(
            $expected,
            $cli->getRequest('/miscellaneous/ping')
        );
    }

    /**
     * Fetches a list of recorded calls for specified customer
     */
    function testGetAllCallRecordings()
    {
        $cli = $this->getApiClient();

        $res = $cli->getRequest('/recordings?customer_id=430217'); // TODO get customer_id from api call also?
        echo "Number of recordings: ".count($res->result['recordings'])."\n";
    }

    function testDownloadCallRecording()
    {
        $cli = $this->getApiClient();

        $res = $cli->getRequest('/recordings?recording_id=7afe76d9-900e-41f5-84cd-2aafab55f56b');

        $soundData = base64_decode($res->result['recordings'][0]['sounddata']);

        $tmpFileName = tempnam('/tmp', 'audio');
        file_put_contents($tmpFileName, $soundData);
        echo "Wrote audio data to ".$tmpFileName."\n";
    }

}
