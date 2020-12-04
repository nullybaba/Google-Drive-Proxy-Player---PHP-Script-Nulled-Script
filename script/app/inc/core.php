<?php

// Defined Constants
define('VERSION','1.5');

define('APP',TRUE);

define('PROOT','');
define('ROOT',dirname(__FILE__,3));


// Start Session
if(!isset($_SESSION)){
  session_start();
}

// Error Reporting
if(!isset($config['debug']) || $config['debug'] == FALSE){
  error_reporting(0);
}else{
  ini_set('display_error',1);
  ini_set('error_reporting',E_ALL);
  error_reporting(-1);
}

// Connect to Database
include(ROOT.'/app/inc/Database.class.php');
$db = new Database($config, $dbinfo);
$config=$db->get_config();

//Set timezone
if(!empty($config['timezone'])){
  date_default_timezone_set($config["timezone"]);
}

// Define Template
define('TEMPLATE', ROOT.'/theme/default');

//Application Helper
include(ROOT.'/app/inc/Main.class.php');
Main::set("config",$config);

// Start Application
include(ROOT.'/app/inc/App.class.php');
$app = new App($db,$config);

require(ROOT.'/app/inc/Link.class.php');
require(ROOT.'/app/inc/User.class.php');
require(ROOT.'/app/lib/JSPacker.php');
include(ROOT.'/app/lib/curl.php');

// Get theme functions file
if(file_exists(ROOT.'/theme/default/functions.php')){
  include(TEMPLATE.'/functions.php');
}

function getThemeURI(){
  return PROOT . '/theme/default';
}


