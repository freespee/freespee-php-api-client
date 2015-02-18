<?php

use Freespee\ApiClient\Client;
use Freespee\ApiClient\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public static function testGetApiClient()
    {
        $config = include __DIR__.'/../settings/settings.php';

        return new Client($config);
    }

    /**
     * @depends testGetApiClient
     * @param Client $cli
     */
    public function testPingUnauthorized(Client $cli)
    {
        $orgUsername = $cli->getUsername();
        $cli->setUsername('');

        $expected = new Response(
            Response::UNAUTHORIZED
        );

        $this->assertEquals(
            $expected,
            $cli->getRequest('/miscellaneous/ping')
        );

        $cli->setUsername($orgUsername);
    }

    /**
     * @depends testGetApiClient
     * @param Client $cli
     */
    public function testPing(Client $cli)
    {
        $expected = new Response(
            Response::OK,
            ['ping' => 'ok']
        );

        $this->assertEquals(
            $expected,
            $cli->getRequest('/miscellaneous/ping')
        );
    }

    /**
     * @depends testGetApiClient
     * @param Client $cli
     * @return mixed
     */
    private function getCustomerInfo(Client $cli)
    {
        $res = $cli->getRequest('/customers');
        return $res->result;
    }

    /**
     * @depends testGetApiClient
     * @param Client $cli
     * @return mixed
     */
    private function getAllCallRecordings(Client $cli)
    {
        // NOTE for this test, we automatically choose the first subcustomer
        $info = $this->getCustomerInfo($cli);
        if (!empty($info['errors'])) {
            $this->fail('Error: '.serialize($info['errors'][0]));
        }
        $this->assertGreaterThan(0, count($info['customers']));

        $custId = $info['customers'][0]['customer_id'];

        $res = $cli->getRequest('/recordings?customer_id='.$custId);
        return $res->result['recordings'];
    }

    /**
     * @depends testGetApiClient
     * @param Client $cli
     */
    public function testDownloadCallRecording(Client $cli)
    {
        // NOTE for this test, we download the first call recording found
        $allRecordings = $this->getAllCallRecordings($cli);

        $this->assertInternalType('array', $allRecordings);
        if (!$allRecordings) {
            $this->markTestSkipped('Recording not available');
        }

        $res = $cli->getRequest('/recordings?recording_id='.$allRecordings[0]['recording_id']);

        $soundData = base64_decode($res->result['recordings'][0]['sounddata']);

        $tmpFileName = tempnam('/tmp', 'audio');
        file_put_contents($tmpFileName, $soundData);
        echo "Wrote audio data for ".$allRecordings[0]['recording_id']." to ".$tmpFileName."\n";
    }
}
