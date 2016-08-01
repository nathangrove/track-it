<?php

  class hasoffers {

    var $api_key;
    var $network_id;
    var $aff_id = 0;

    function __construct($api_key,$network_id){
      
      if ($api_key == '' OR $network_id == '')
        return "Invalid network credentials\n";

      $this->api_key = $api_key;
      $this->network_id = $network_id;

      return $this;
    }


    function findMyOffers(){
      $offers = $this->execute("findMyOffers",array("limit"=>'1000'));
      $network_offers = array();
      $network_offer_ids = array();

      foreach($offers->response->data->data AS $network_offer_id => $offer){

        $offer = $offer->Offer;
        $offer->network_offer = $offer->id;
        $offer->date = date("Y-m-d");
        $offer->payout = $offer->default_payout;
        $offer->daily_cap = $offer->conversion_cap;
        $offer->monthly_cap = $offer->monthly_conversion_cap;
        $offer->pause_date = $offer->expiration_date;
        # $offer->terms = $offer->terms_and_conditions;
        $offer->restrictions = $offer->terms_and_conditions;
        $offer->preview_link = $offer->preview_url;
        if ($this->aff_id == 0){
          $id_fetch = $this->execute("generateTrackingLink",array("offer_id"=>$offer->id));
          #print_r($id_fetch);exit;
          $this->aff_id = $id_fetch->response->data->affiliate_id;
        }
        $offer->unique_url = "http://tracking.$this->network_id.com/aff_c?offer_id=$offer->id&aff_id=$this->aff_id";

        $offer->country = array();

        if($offer->use_target_rules == 1){
          $countries = $this->execute("getTargetCountries",array("ids"=>array($network_offer_id)));
          #print_r($countries);
          if (count($countries->response->data[0]->countries) > 0){
            foreach($countries->response->data[0]->countries AS $country){
              $offer->country[] = $country->name;
            }
          }
        }

        $network_offers[$network_offer_id] = $offer;

      }
      
      return $network_offers;

    }



    function execute($method,$params=array()){

      $url = "http://api.hasoffers.com/v3/Affiliate_Offer.json?Method=$method&api_key=$this->api_key&NetworkId=$this->network_id";


      if (count($params) > 0){
        $params = http_build_query($params, '', '&');
        $url .= "&$params";
      }
      
      #print $url;

      $curl = curl_init($url);
      curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($curl);
      curl_close($curl);

      $response = json_decode($response);
      
      $fw = @fopen("hasoffers.response.txt","a");
      @fwrite($fw, print_r($response,true));
      @fclose($fw);

      return $response;

    }

  }