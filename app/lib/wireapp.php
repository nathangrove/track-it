<?

class wireapp {

  var $uid;
  var $conf;

  function wireapp($conf) {

    # some vars
    $this->conf = $conf;

    # start the session
    if ($conf["app.base"] == "pdf" && $conf["app.call"] == "download") { 
      session_cache_limiter('private');
    }
    if ($conf["session.cookie.path"] != "") {
      session_set_cookie_params(0,$conf["session.cookie.path"],$conf["session.cookie.domain"]);
    }
    session_start();


    if (!strrpos($_SERVER['REQUEST_URI'],"favicon")){
      $log = new dbo("log");
      $log->request = $_SERVER['REQUEST_URI'];
      $log->ip = $_SERVER['REMOTE_ADDR'];
      $log->user = intval($_SESSION['wireapp.uid']);
      $log->date = date("Y-m-d H:i:s");
      $log->insert();
    }
    
  }


  function process($base="",$call="") {

    # the call
    if ($call == "") { 
      $call = $base;
      $base = "";
    }
    
    $this->conf["base"] = $base;
    $this->conf["call"] = $call;

    
    # base/lib directories
    $basedir = $this->conf["directory.base"];
    $libdir = $this->conf["directory.lib"];

    # check for the class file
    if ($base == "")
      $classfile = addslashes($basedir . "/_view.php");
    else
      $classfile = addslashes($basedir . "/" . str_replace(":","/",$base) . "/_view.php");

    require_once $basedir . "/_super.php";
    
    if (!is_file($classfile)) {
      $obj = new wireapp_super($this);
      include $obj->template();
      exit;
    }
    
    require_once $classfile;

    # instantiate 
    $obj = new wireapp_base($this);
    $method = array($obj, $call);
    if (is_callable($method)) {
      
      $auth = $call . "_auth";
      if (!isset($obj->$auth) || (isset($obj->$auth) && $obj->$auth == true))
        $obj->auth();
        
      $obj->$call();
      
    }
    else {
      $obj->template("",true);
    }
      

    # bow out
    exit;

  }




}


?>
