<?php
  
  #####################################################
  # config section
  #####################################################
  
  $app_dir = "/home/tracker/app";
  $app_prefix = "/app";
  $app_branding = "Tracking App";
  
  $db_name = "tracker";
  $db_login = "tracker";
  $db_password = "tr@ck3r";
  $db_host = "localhost";
  
  #####################################################
  
  $path = ini_get("include_path");
  ini_set("include_path", $path . ":$app_dir/lib/pear/");

  define('DB_DATAOBJECT_NO_OVERLOAD',true);
  
  require_once "PEAR.php";
  require_once "DB/DataObject.php";
  require_once "$app_dir/lib/wireapp.php";
  
  
  # config vars
  $config["url.prefix"] = $app_prefix;
  $config["directory.base"] = "$app_dir/base";
  $config["directory.lib"] = "$app_dir/lib";
  $config["directory.files"] = "$app_dir/files";
  $config["session.directory"]  = "$app_dir/session";
  $config["branding"] = $app_branding;
  

  $options = &PEAR::getStaticProperty('DB_DataObject','options');
  $options = array(
      'database'          => "mysql://$db_login:$db_password@$db_host/$db_name",
      'schema_location'   => "$app_dir/dbo",
      'class_location'    => "$app_dir/dbo",
      'require_prefix'    => 'dbo/',
      'class_prefix'      => 'dbo_',
      'db_driver'         => 'DB',
      'quote_identifiers' => true,
      'proxy'             => 'full'
  );

  # easy access class
  class dbo extends DB_DataObject {
     function __construct($o,$i=0) {
       $this->__table = $o;
       if ($i>0)
         $this->get($i);
    }
  }
  
  # those darn slashes
  function stripslashes_deep($value) {
    return (is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value));
  }

  if (get_magic_quotes_gpc()) {
    $_REQUEST    = array_map('stripslashes_deep', $_REQUEST);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
  }


  $path = $_SERVER['PATH_INFO'];
  $parts = explode("/",$path);
  if (count($parts) == 1) {
    $base = $parts[0];
    $call = "";
  }
  else {
    $call = array_pop($parts);
    $base = join(":",$parts);
  }
  $config["app.base"] = $base;
  $config["app.call"] = $call;

  $app = new wireapp($config);
  $app->process($base,$call);



?>