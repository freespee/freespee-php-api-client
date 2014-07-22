<?php

class ApiClientTest extends \PHPUnit_Framework_TestCase
{
    static function testGetApiClient()
    {
        $freespee = new \Freespee\ApiClient();

        $apiSettingsFile = realpath(__DIR__.'/../../settings').'/settings.php';
        require $apiSettingsFile;

        return $freespee;
    }

    /**
     * @depends testGetApiClient
     */
    function testPingUnauthorized(\Freespee\ApiClient $cli)
    {
        $orgUsername = $cli->getUsername();
        $cli->setUsername('');

        $expected = new \Freespee\ApiResponse();
        $expected->httpCode = 401; // Unauthorized
        $expected->result = null;

        $this->assertEquals(
            $expected,
            $cli->getRequest('/miscellaneous/ping')
        );

        $cli->setUsername($orgUsername);
    }

    /**
     * @depends testGetApiClient
     */
    function testPing(\Freespee\ApiClient $cli)
    {
        $expected = new \Freespee\ApiResponse();
        $expected->httpCode = 200; // OK
        $expected->result = array('ping' => 'ok');

        $this->assertEquals(
            $expected,
            $cli->getRequest('/miscellaneous/ping')
        );
    }

    /**
     * @depends testGetApiClient
     */
    private function getCustomerInfo(\Freespee\ApiClient $cli)
    {
        $res = $cli->getRequest('/customers');
        return $res->result;
    }

    /**
     * @depends testGetApiClient
     */
    private function getAllCallRecordings(\Freespee\ApiClient $cli)
    {
        // NOTE for this test, we automatically choose the first subcustomer
        $info = $this->getCustomerInfo($cli);
        $this->assertGreaterThan(0, count($info['customers']));

        $custId = $info['customers'][0]['customer_id'];

        $res = $cli->getRequest('/recordings?customer_id='.$custId);
        return $res->result['recordings'];
    }

    /**
     * @depends testGetApiClient
     */
    function testDownloadCallRecording(\Freespee\ApiClient $cli)
    {
        // NOTE for this test, we download the first call recording found
        $allRecordings = $this->getAllCallRecordings($cli);

        $res = $cli->getRequest('/recordings?recording_id='.$allRecordings[0]['recording_id']);

        $soundData = base64_decode($res->result['recordings'][0]['sounddata']);

        $tmpFileName = tempnam('/tmp', 'audio');
        file_put_contents($tmpFileName, $soundData);
        echo "Wrote audio data for ".$allRecordings[0]['recording_id']." to ".$tmpFileName."\n";
    }

}
