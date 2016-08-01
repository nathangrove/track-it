<?



class wireapp_base extends wireapp_super {

  
  ##########################################
  # index
  ##########################################
  function index() {
    
    $network = new dbo("network");
    $network->query("
      select 
        network.*,
        network_def.type
      from
        network,
        network_def 
      where 
        network.def = network_def.id
        and network.user = $this->uid");

    $active = 'network';

    include $this->template();

  }
  ##########################################

  
  ##########################################
  # add
  ##########################################
  function add() {
    
    if ($this->process){
      
      $network = new dbo("network");
      $network->user = $this->uid;
      $network->name = $this->p('name');
      $network->affiliate_id = $this->p('affiliate_id');
      $network->def = $this->p('def');
      $network->api_key = $this->p('api_key');
      $network->date = date("Y-m-d H:i:s");
      $network->insert();

      $this->redirect("/network/index");

    }

    $network_def =  new dbo("network_def");
    $network_def->find();

    $active = 'network';

    include $this->template();

  }
  ##########################################

  
  ##########################################
  # edit
  ##########################################
  function edit() {
    
    $network = new dbo("network",intval($this->p('id')));
    if ($network->user != $this->uid)
      $this->error("Invalid network");

    if ($this->process){
      
      $network->name = $this->p('name');
      $network->affiliate_id = $this->p('affiliate_id');
      $network->def = $this->p('def');
      $network->api_key = $this->p('api_key');
      $network->update();

      $this->redirect("/network/index");

    }

    $network_def =  new dbo("network_def");
    $network_def->find();

    $active = 'network';

    include $this->template();

  }
  ##########################################


  
  ##########################################
  # fetch
  ##########################################
  function fetch() {
#    error_reporting(E_ALL);
#ini_set('display_errors', '1');
    set_time_limit(0);

    $network = new dbo("network",intval($this->p('id')));
    $network_def = new dbo("network_def",intval($network->def));

    if ($network_def->type == 'CAKE'){
      require_once $this->libdir."/cake.class.php";
      $cake = new cake($network->api_key,$network->affiliate_id,$network_def->network_url);
      $offers = $cake->get_offers();

    } else if($network_def->type == 'HASOFFERS'){
      require_once $this->libdir."/hasoffers.class.php";
      $hasoffers = new hasoffers($network->api_key,$network_def->network_url);
      $offers = $hasoffers->findMyOffers();
      
    } else if($network_def->type == 'MAXBOUNTY'){
      require_once $this->libdir."/maxbounty.class.php";
      $maxbounty = new maxbounty($network->affiliate_id,$network->api_key);
      if ($maxbounty == false)
        $this->error("Invalid API credentials");
      $offers = $maxbounty->get_campaigns();

    } else {
      $this->error("Invalid network");
    }

    foreach($offers AS $net_offer){
      
      if ($net_offer->unique_url == 'hidden')
        continue;

      $offer = new dbo('offer');
      $offer->user = $this->uid;
      $offer->network = $network_def->id;
      $offer->network_offer = $net_offer->network_offer;

      if ($offer->find(true) == 0){
        $offer->code = time().".".rand(111111111,999999999);
        $offer->date = date('Y-m-d H:i:s');
      }

      $offer->name = $net_offer->name;
      $offer->payout = $net_offer->payout;
      if ($network_def->type == 'HASOFFERS')
        $offer->url = $net_offer->unique_url . "&aff_sub2=[CLICK_ID]";
      else if ($network_def->type == 'CAKE')
        $offer->url = $net_offer->unique_url . "&s2=[CLICK_ID]";
      else
        $offer->url = $net_offer->unique_url;
      $offer->preview = $net_offer->preview_link;
      $offer->description = $net_offer->description;
      $offer->restrictions = $net_offer->restrictions;

      if ($offer->id > 0)
        $offer->update();
      else
        $offer->insert();

      if ($network_def->type == 'CAKE')
        $cake->set_postback("http://$this->domain/postback/#s2#/#price#",$net_offer->network_camp);
    }


    $this->redirect("/offer/index");    

  }
  ##########################################


  
  ##########################################
  # delete
  ##########################################
  function delete() {
    
    $network = new dbo("network",intval($this->p('id')));
    if ($network->user != $this->uid)
      $this->error("Invalid network");

    $network->delete();

    $this->redirect("/network/index");
    
  }
  ##########################################



}




?>