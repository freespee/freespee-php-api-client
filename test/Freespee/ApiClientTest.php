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

    private function getCustomerInfo()
    {
        $cli = $this->getApiClient();

        $res = $cli->getRequest('/customers');
        return $res->result;
    }

    private function getAllCallRecordings()
    {
        // NOTE for this test, we automatically choose the first subcustomer
        $info = $this->getCustomerInfo();
        $this->assertGreaterThan(0, count($info['customers']));

        $custId = $info['customers'][0]['customer_id'];

        $cli = $this->getApiClient();

        $res = $cli->getRequest('/recordings?customer_id='.$custId);
        return $res->result['recordings'];
    }

    function testDownloadCallRecording()
    {
        $allRecordings = $this->getAllCallRecordings();

        $cli = $this->getApiClient();

        $res = $cli->getRequest('/recordings?recording_id='.$allRecordings[0]['recording_id']);

        $soundData = base64_decode($res->result['recordings'][0]['sounddata']);

        $tmpFileName = tempnam('/tmp', 'audio');
        file_put_contents($tmpFileName, $soundData);
        echo "Wrote audio data for ".$allRecordings[0]['recording_id']." to ".$tmpFileName."\n";
    }

}
