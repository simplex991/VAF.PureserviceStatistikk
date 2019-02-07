<?php


$version = "#{Version}";
$serverName = "vaf-root-sqla3";
$serverPassword = "#{Databasepassord}";
// Change password with #{Databasepassord} before a commit
$connectionOptions = array("Database"=>"Pureservice3", "Uid"=>"SPlese", "PWD"=>"Tpiwtlteraa1");

 //Establishes the connection
$connect = sqlsrv_connect($serverName, $connectionOptions);

//If connection fails
// if (condition) {
//   # code...
// }
