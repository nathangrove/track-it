<?php


class maxbounty{

  var $api_key;
  var $client;

  function __construct($username,$password){
    $soap_server = 'http://www.maxbounty.com/api/api.cfc?wsdl';

    $this->client = new soapclient($soap_server);
    $this->api_key = $this->client->getKey($username,$password);

    if ($this->api_key == '')
      return false;
    else
      return true;
  }

  function get_campaigns(){

    $campaigns = $this->client->campaignList($this->api_key);

    $return = array();
    foreach($campaigns as $id => $campaign){

      try {
        $creatives = $this->client->getCampaignCreatives($this->api_key,$campaign['OFFER_ID']);
        if (count($creatives) < 1)
          continue;
      } catch (SoapFault $E) {  
        print $E->faultstring; 
        continue;
      } 

      $pattern = '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';
      preg_match_all($pattern, $creatives[0]['CODE'], $matches, PREG_PATTERN_ORDER);
      $unique_url = $matches[0][0];

      $offer = new stdClass;        
      $offer->name = $campaign['NAME'];
      $offer->network_offer = $campaign['OFFER_ID'];
      $offer->payout = preg_replace("/[^0-9\.]/", "", $campaign['RATE']);
      $offer->description = $campaign['DESCRIPTION'];
      $offer->preview_link = $campaign['PREVIEW_URL'];
      $offer->restrictions = $campaign['COUNTRIES'];
      $offer->unique_url = $unique_url;
      $offer->date = date("Y-m-d");

      $return[$offer->network_offer] = $offer;
    }

    return $return;
  }

}
?>