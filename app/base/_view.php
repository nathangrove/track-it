<?php

class wireapp_base extends wireapp_super {
 
  ##########################################
  # index 
  ##########################################
  function index() {

    $click = new dbo('click');
    $click->query("
      select
        SUM(IF(date >= curdate(), 1, 0) AND type='CLICK') AS 'clicks_today',
        SUM(IF(date >= curdate(), 1, 0) AND type='IMPRESSION') AS 'impressions_today',
        SUM(IF(type='CLICK',1,0)) as 'total_clicks',
        SUM(IF(conversion > curdate(), 1, 0)) AS 'conversions_today',
        SUM(IF(conversion > curdate(), revenue, 0)) AS 'revenue_today',
        SUM(IF(conversion IS NOT NULL, 1, 0)) AS 'total_conversions',
        SUM(IF(conversion IS NOT NULL, revenue, 0)) AS 'total_revenue',
        SUM(IF(type='IMPRESSION',1,0)) AS 'total_impressions'
      from
        click 
      where 
        click.user = $this->uid");

    $click->fetch();

    $offer = new dbo('offer');
    $offer->user = $this->uid;
    $offer->find();

    $network = new dbo('network');
    $network->query ("select count(distinct(network)) as 'count' from offer where user = ".intval($this->uid));
    $network->fetch();

    $active = 'index';
    
    include $this->template();
  }
  ##########################################
 
  ##########################################
  # report 
  ##########################################
  function report() {

    if ($this->process){

      $data_arr = $this->p('data');
      $geo_arr = $this->p('geo');
      $stat_arr = $this->p('stats');

      require_once $this->libdir."/ip2loc/IP2Location.php";
      require_once $this->libdir."/Browser.php";

      $offer = new dbo("offer",intval($this->p('offer')));
      if ($offer != $this->uid)
        $this->error("Invalid offer");

      $clicks = "SELECT offer.name, click.* FROM click,offer WHERE offer.id = click.offer AND click.user = $this->uid ";

      if ($this->p('offer') > 0)
        $clicks .= " AND click.offer = $offer->id";

      if ($this->p('type') == "CLICK")
        $clicks .= " AND click.type = 'CLICK'";

      if ($this->p('type') == 'CONVERISON')
        $clicks .= " AND click.type = 'CLICK' AND click.conversion IS NOT NULL";

      if ($this->p('type') == "IMPRESSION")
        $clicks .= " AND click.type = 'IMPRESSION'";

      $clicks .= " AND click.date > '".date("Y-m-d 00:00:00",strtotime($this->p('startdate')))."'";
      $clicks .= " AND click.date < '".date("Y-m-d 23:59:59",strtotime($this->p('enddate')))."'";
      $clicks .= " ORDER BY click.date DESC";

      $click = new dbo("click");
      $click->query($clicks);

      $report = array();
      $headers = array();
      while ($click->fetch()){

        $report_line = array();
        $headers[0] = 'Offer';
        $report_line[] = $click->name;

        if ($this->p('type') == ''){
          $headers[1] = 'TYPE';
          $report_line[] = $click->type;
        }
        
        foreach($data_arr AS $data){

          if ($data == 'browser'){
            $headers[2] = 'Browser';
            $browser = new Browser();
            $report_line[] = $browser->getBrowser($click->useragent);
          }
        
          if ($data == 'agent'){
            $headers[3] = 'Useragent';
            $report_line[] = $click->useragent;
          }

          if ($data == 'ip'){
            $headers[4] = 'IP Address';
            $report_line[] = $click->ip;
          }
          
          if ($data == 'referer'){
            $headers[5] = 'Referer';
            $report_line[] = $click->referer;
          }

          if ($data == 'ts1'){
            $headers[6] = 'tS1';
            $report_line[] = $click->ts1;
          }

          if ($data == 'ts2'){
            $headers[7] = 'tS2';
            $report_line[] = $click->ts2;
          }

          if ($data == 'ts3'){
            $headers[8] = 'ts3';
            $report_line[] = $click->ts3;
          }

          if ($data == 'ts4'){
            $headers[9] = 'ts4';
            $report_line[] = $click->ts4;
          }

        }

        if (count($this->p('geo')) > 0){
          $loc = new IP2Location($this->libdir."/ip2loc/IP2LOCATION-LITE-DB11.BIN");
          $geo_record = $loc->lookup($click->ip, IP2Location::ALL);
        }

        foreach($geo_arr AS $geo){

          if ($geo == 'city'){
            $headers[10] = 'City';
            $report_line[] = $geo_record->cityName;
          }
          if ($geo == 'state'){
            $headers[11] = 'State';
            $report_line[] = $geo_record->regionName;
          }
          if ($geo == 'zip'){
            $headers[12] = 'Zip';
            $report_line[] = $geo_record->zipCode;
          }
          if ($geo == 'country'){
            $headers[13] = 'Country';
            $report_line[] = $geo_record->countryName;
          }
        }

        foreach($stat_arr AS $stat){

          
          if ($stat == 'revenue'){
            $headers[14] = 'Revenue';
            $report_line[] = $click->revenue == '' ? "0.00" : $click->revenue;
          }

          if ($stat == 'date'){
            $headers[15] = 'Date';
            $report_line[] = $click->date;
          }
        }
        
        $report[] = $report_line;

      }

      if ($this->p('download') != ''){
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=report.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        print implode(",",$headers)."\n";
        foreach($report AS $report_line){
          print implode(",",$report_line)."\n";
        }

        exit;
      }
      
    }

    $sel_offer = new dbo("offer");
    $sel_offer->user = $this->uid;
    $sel_offer->find();

    $active = 'report';

    include $this->template();
  }
  ###########################################
  
  
  ##########################################
  # logout
  ##########################################
  function logout() {
    $this->_logout();
    $this->redirect("/index");
  }
  ##########################################
  



}
?>
