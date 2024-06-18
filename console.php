<?php
require __DIR__ . '/vendor/autoload.php';

$db = new \db\PdoDatabase('mysql:host=mysql.local;dbname=wisebits', 'root', 'rootroot');
$logger = new \loggers\EchoLogger();
$application = new \base\ConsoleApplication($db, $logger);
//$exitCode = $application->run();
//exit($exitCode);

$model = \models\User::findOne(1);

var_dump($model);
