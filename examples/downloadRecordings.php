#!/usr/bin/env php
<?php
/**
 * Example how to use the API to download all your call recordings
 */

require __DIR__.'/../bootstrap.php';

$cli = new \Freespee\ApiClient();
$cli->setBaseUrl('https://api.analytics.freespee.com/2.4.9');

$apiSettingsFile = realpath(__DIR__.'/../settings').'/settings.php';
require $apiSettingsFile;


// get all subcustomers
foreach ($cli->getRequest('/customers')->result['customers'] as $customer) {
    echo 'Requesting recordings for customer_id '.$customer['customer_id'].' ('.$customer['name'].")\n";

    // get all recordings for current subcustomer
    $recordings = $cli->getRequest('/recordings?customer_id='.$customer['customer_id'])->result['recordings'];
    if (count($recordings) == 0) {
        echo "No recordings found\n";
        continue;
    }
    echo 'Found '.count($recordings).' recordings, downloading...'."\n";

    $dstDir = __DIR__.'/'.$customer['customer_id'];
    if (!is_dir($dstDir)) {
        mkdir($dstDir);
    }

    foreach ($recordings as $recording) {
        $outFileName = $dstDir.'/'.$recording['recording_id'];

        if (file_exists($outFileName)) {
            continue;
        }

        echo 'Downloading '.$recording['recording_id'].' ...'."\n";

        // download specific recording
        $res = $cli->getRequest('/recordings?recording_id='.$recording['recording_id']);

        if ($res->result['recordings'][0]['expired'] !== '0') {
            echo "Recording has expired, skipping\n";
            continue;
        }

        $soundData = base64_decode($res->result['recordings'][0]['sounddata']);

        file_put_contents($outFileName, $soundData);
        echo 'Wrote '.strlen($soundData).' bytes to '.$outFileName."\n";
    }
}
