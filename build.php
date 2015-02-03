#!/usr/bin/env php
<?php
chdir(__DIR__);

$returnStatus = null;
passthru('composer install --dev', $returnStatus);
if ($returnStatus !== 0) {
    exit(1);
}

passthru(
    './vendor/bin/phpcs --standard=' . __DIR__ . '/vendor/chadicus/coding-standard/Chadicus -n src tests *.php',
    $returnStatus
);
if ($returnStatus !== 0) {
    exit(1);
}

passthru('./vendor/bin/phpunit', $returnStatus);
if ($returnStatus !== 0) {
    exit(1);
}

$xml = new SimpleXMLElement(file_get_contents('clover.xml'));
foreach ($xml->xpath('//file/metrics') as $metric) {
    if ((int)$metric['elements'] !== (int)$metric['coveredelements']) {
        fputs(STDERR, "Code coverage was NOT 100%\n");
        exit(1);
    }
}

unlink('clover.xml');

echo "Code coverage was 100%\n";
