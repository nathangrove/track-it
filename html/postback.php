<?php

  mysql_connect("localhost","tracker","tr@ck3r");
  mysql_select_db("tracker");


  $parts = explode("/",$_SERVER['PATH_INFO']);
  $click_id = $parts[1];
  $revenue = $parts[2];
  
  mysql_query("UPDATE click SET conversion=NOW(), revenue='$revenue' WHERE id='$click_id'");

?>