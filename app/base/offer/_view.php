<?



class wireapp_base extends wireapp_super {

  
  ##########################################
  # index
  ##########################################
  function index() {
    
    $offer = new dbo("offer");
    $offer->query("
      select 
        offer.*,
        network.name as 'network_name'
        
      from 
        offer
      left join  network
        on offer.network = network.id
      where 
        offer.user = $this->uid
      group by offer.id
      order by offer.name");

    $active = 'offer';

    include $this->template();

  }
  ##########################################

  
  ##########################################
  # add
  ##########################################
  function add() {
    
    if ($this->process){
      
      $offer = new dbo("offer");
      $offer->user = $this->uid;
      $offer->name = $this->p('name');
      $offer->url = $this->p('url');
      $offer->network = $this->p('network');
      $offer->description = $this->p('description');
      $offer->restrictions = $this->p('restrictions');
      $offer->payout = $this->p('payout');
      $offer->preview = $this->p('preview');
      $offer->code = $this->p('code');
      $offer->date = date("Y-m-d H:i:s");
      $offer->insert();

      $this->redirect("/offer/index");

    }

    $code = time().".".rand(111111111,999999999);

    $network = new dbo('network');
    $network->user = $this->uid;
    $network->find();


    $active = 'offer';

    include $this->template();

  }
  ##########################################

  
  ##########################################
  # edit
  ##########################################
  function edit() {
    
    $offer = new dbo("offer",intval($this->p('id')));
    if ($offer->user != $this->uid)
      $this->error("Invalid offer");

    if ($this->process){
      
      $offer->name = $this->p('name');
      $offer->url = $this->p('url');
      $offer->network = $this->p('network');
      $offer->description = $this->p('description');
      $offer->restrictions = $this->p('restrictions');
      $offer->payout = $this->p('payout');
      $offer->preview = $this->p('preview');
      $offer->code = $this->p('code');
      $offer->date = date("Y-m-d H:i:s");
      $offer->update();

      $this->redirect("/offer/index");

    }

    $network = new dbo('network');
    $network->user = $this->uid;
    $network->find();

    $active = 'offer';

    include $this->template();

  }
  ##########################################

  
  ##########################################
  # delete
  ##########################################
  function delete() {
    
    $offer = new dbo("offer",intval($this->p('id')));
    if ($offer->user != $this->uid)
      $this->error("Invalid offer");

    $offer->delete();

    $this->redirect("/offer/index");
    
  }
  ##########################################



}




?>