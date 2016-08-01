<?php

  mysql_connect("localhost","tracker","tr@ck3r");
  mysql_select_db("tracker");


  $parts = explode("/",$_SERVER['PATH_INFO']);
  $code = mysql_real_escape_string($parts[1]);

  $offer = mysql_query("SELECT * FROM offer WHERE code='$code'");
  if (mysql_num_rows($offer) < 1)
    exit;

  $offer = mysql_fetch_object($offer);

  @mysql_query("
    INSERT INTO click 
    (
      user,
      offer,
      ip,
      useragent,
      ts1,
      ts2,
      ts3,
      ts4,
      referer,
      date
    ) VALUES (
      '$offer->user',
      '$offer->id',
      '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."',
      '".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."',
      '".mysql_real_escape_string($_GET['ts1'])."',
      '".mysql_real_escape_string($_GET['ts2'])."',
      '".mysql_real_escape_string($_GET['ts3'])."',
      '".mysql_real_escape_string($_GET['ts4'])."',
      '".mysql_real_escape_string($_SERVER['HTTP_REFERER'])."',
      NOW())");

  $click_id = mysql_insert_id();

  $rurl = str_replace("[CLICK_ID]",$click_id,$offer->url);

  if (!strstr($rurl,"?"))
    $rurl .= "?";

  foreach($_GET AS $key=>$val){
    
    if ($key == 'ts1' || $key == 'ts2' || $key == 'ts3' || $key == 'ts4')
      continue;

    $rurl .= "&$key=$val";
  }

  if(substr($rurl, 0, 7) == "http://")
    $rurl = substr($rurl,7);
  
  $rurl = "/rd.php?d=" . rawurlencode($rurl);
  
  header("Location: ".$rurl);

?>