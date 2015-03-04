<?php
//$path= realpath(__DIR__ . '/../../../..');
$dsn=ANAX_INSTALL_PATH .'data/wgtotw.db';
return [
 // 'dsn'     => "sqlite:./../data/users.db",
 'dsn'     => "sqlite:$dsn",

 // 'verbose' => true,
 //'debug_connect' => 'true',
];


