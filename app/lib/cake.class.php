<?php

#####################################################
# Cake API Library (Developed and tested with A2Ads)
#
#	Return: -1 API Call Error
#          0 Class Error
#
#
#####################################################

class cake {

	var $api_key 					= '';       # API Key
	var $affiliate_id 		= '';				# Affiliate ID
	var $url 							= '';				# API's URL
	var $statuses 				= array();	# Associative Array to store available campaign_statuses and their codes


	function __construct($api_key,$affiliate_id,$url,$camp_status=false){
		$this->api_key = $api_key;
		$this->affiliate_id = $affiliate_id;
		$this->url = $url;

		# get campaign statuses on init?
		if ($camp_status){
			$this->campaign_status();
      #print_r($this->statuses);exit;
		}
	}


	function campaign_status(){
		$request = "http://$this->url/affiliates/api/2/offers.asmx/GetOfferStatuses?api_key=$this->api_key&affiliate_id=$this->affiliate_id";
		if (($xml = simplexml_load_file($request)) !== FALSE){

			if ($xml->success){
				foreach($xml->offer_statuses->offer_status AS $status){
					# store them as an associative array for faster searching
					$this->statuses[strtoupper((string)$status->status_name[0])] = (string) $status->status_id[0];
				}

				return true;
			} else {
				return "-1";
			}
		} else {
			return "-1";
		}
	}

	################################################
	#
	# status = campaign status... active by default
	#
	################################################
	function get_offers($status=1){
    
    if ($status==100){
      return $this->get_sample();
    }

		$request = "http://$this->url/affiliates/api/4/offers.asmx/OfferFeed?api_key=$this->api_key&affiliate_id=$this->affiliate_id&campaign_name=&media_type_category_id=0&vertical_category_id=0&vertical_id=0&offer_status_id=$status&tag_id=0&start_at_row=1&row_limit=0";
		#print $request;exit;
		$campaigns = array();
    $xml = simplexml_load_file($request);
    if ($xml !== FALSE){
      # print_r($xml);exit;
      if ($xml->success){

        # seperate call to fetch the affiliate link
        foreach($xml->offers->offer AS $offer_xml){
          #print_r($offer_xml);#exit;
        	$campaign = new stdClass();
        	$campaign->network_camp = (string) $offer_xml->campaign_id[0];
          $campaign->unique_url = '';

          if ($offer_xml->hidden == 'true'){
            
            $campaign->unique_url = 'hidden';
          
          } elseif (intval($campaign->network_camp) > 0){

            $camp_req = "http://$this->url/affiliates/api/2/offers.asmx/GetCampaign?api_key=$this->api_key&affiliate_id=$this->affiliate_id&campaign_id=$campaign->network_camp";

            $camp = simplexml_load_file($camp_req);
            if ($camp !== FALSE && $camp->success == 'true'){
              #print_r($camp);
            	if (isset($camp->campaign->creatives->creative_info[0])){
  	            foreach ($camp->campaign->creatives->creative_info AS $creative){
  	              if ($creative->creative_type->type_id == 1){
  	                $campaign->unique_url = (string) $creative->unique_link[0];
  	                break;
  	              }
  	            }
          		}
            }
          }

          $campaign->name          = (string) $offer_xml->offer_name[0];
          $campaign->date          = date("Y-m-d");
          $campaign->payout        = preg_replace("/[^0-9\.]/", "",$offer_xml->payout);
          $campaign->network_offer = (string) $offer_xml->offer_id[0];
          $campaign->description   = (string) $offer_xml->description[0];
          $campaign->terms         = (string) $offer_xml->advertiser_extened_terms[0];
          $campaign->preview_link  = (string) $offer_xml->preview_link[0];
          $campaign->status        = (string) $offer_xml->offer_status->status_name;
          $campaign->restrictions  = (string) $offer_xml->restrictions[0];
          $campaign->country = array();

          if (isset($offer_xml->allowed_countries->country)){
	          foreach($offer_xml->allowed_countries->country AS $country){
	        	  $campaign->country[] = (string) $country->country_name[0];
	          }
            #print_r($offer_xml->allowed_countries->country);#exit;
          #print_r($campaign);exit;
          }
          
	        $campaigns[] = $campaign;
        }
      }

      #print base64_encode(serialize($campaigns));exit;
      return $campaigns;
    }

    return false;


	}

  function set_postback($url,$camp){
    $req = "http://$this->url/affiliates/api/2/offers.asmx/SetPostbackURL?api_key=$this->api_key&affiliate_id=$this->affiliate_id&campaign_id=$camp";
    $postback = rawurlencode($url);
    $req .= "&postback_url=$postback";
    
    $camp = simplexml_load_file($req);
  }
}