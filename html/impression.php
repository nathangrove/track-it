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
      type,
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
      'IMPRESSION',
      '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."',
      '".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."',
      '".mysql_real_escape_string($_GET['ts1'])."',
      '".mysql_real_escape_string($_GET['ts2'])."',
      '".mysql_real_escape_string($_GET['ts3'])."',
      '".mysql_real_escape_string($_GET['ts4'])."',
      '".mysql_real_escape_string($_SERVER['HTTP_REFERER'])."',
      NOW())");

  header( "Content-type: image/gif"); 
  header( "Expires: Wed, 11 Nov 1998 11:11:11 GMT"); 
  header( "Cache-Control: no-cache"); 
  header( "Cache-Control: must-revalidate"); 
  printf("%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%", 71,73,70,56,57,97,1,0,1,0,128,255,0,192,192,192,0,0,0,33,249,4,1,0,0,0,0,44,0,0,0,0,1,0,1,0,0,2,2,68,1,0,59);

?>