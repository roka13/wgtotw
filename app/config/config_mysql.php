<?php

return [

// local
    'dsn'     => "mysql:host=localhost;dbname=test;",
    'username'        => "root",
    'password'        => "",
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'    => "test_",
 //   'verbose' => true,
// 'debug_connect' => 'true',
];
/*
//bth
return[
'dsn'    = 'mysql:host=blu-ray.student.bth.se;dbname=roka13;';
'username'      = 'roka13';
'password'      = 'Jj/24lJ:';
'driver_options' = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
//   'verbose' => true,
  // 'debug_connect' => 'true',
  ];
*/
