<?php

if(!file_exists("app/config.php")){
  header("Location: install");
  exit;
}

require_once(__DIR__.'/app/config.php');

$app->run();





















// cfd
