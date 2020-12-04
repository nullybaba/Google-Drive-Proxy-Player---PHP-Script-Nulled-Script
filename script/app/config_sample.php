<?php


// Database Configuration
$dbinfo = [
  'host' => 'RHOST', // Your mySQL Host (usually Localhost)
  'username' => 'RUSER', // Your mySQL Databse username
  'password' => 'RPASS', //  Your mySQL Databse Password
  'db' => 'RDB' // The database where you have dumped the included sql file
];


$config = [
  'timezone' => date_default_timezone_get(),
  'debug' => false
];

define('APIKEY','RAPIKEY');


function dnd($data){
  echo '<pre>';
  print_r($data);
  echo '</pre>';
  die();
}


if (strpos($_SERVER['REQUEST_URI'], '/stream/') === false) {
  include ('inc/core.php');
}













// asd
