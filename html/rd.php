<?

  $x = 'RldTB14FAAB4AAVfAAAPoAAAGAEARBEQAAAAfxMSBQAAPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIj4gPHhtcDpDcmVhdG9yVG9vbD5BZG9iZSBGbGFzaCBQcm9mZXNzaW9uYWwgQ1M1LjUgLSBidWlsZCAzMjU8L3htcDpDcmVhdG9yVG9vbD4gPHhtcDpDcmVhdGVEYXRlPjIwMTEtMDUtMjNUMTQ6MDU6MzYtMDU6MDA8L3htcDpDcmVhdGVEYXRlPiA8eG1wOk1ldGFkYXRhRGF0ZT4yMDExLTA1LTIzVDE0OjA3OjExLTA1OjAwPC94bXA6TWV0YWRhdGFEYXRlPiA8eG1wOk1vZGlmeURhdGU+MjAxMS0wNS0yM1QxNDowNzoxMS0wNTowMDwveG1wOk1vZGlmeURhdGU+IDwvcmRmOkRlc2NyaXB0aW9uPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iPiA8ZGM6Zm9ybWF0PmFwcGxpY2F0aW9uL3gtc2hvY2t3YXZlLWZsYXNoPC9kYzpmb3JtYXQ+IDwvcmRmOkRlc2NyaXB0aW9uPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiPiA8eG1wTU06RGVyaXZlZEZyb20gcmRmOnBhcnNlVHlwZT0iUmVzb3VyY2UiPiA8c3RSZWY6aW5zdGFuY2VJRD54bXAuaWlkOkY3N0YxMTc0MDcyMDY4MTE4MjJBOTg2QjU4MUNBNUZDPC9zdFJlZjppbnN0YW5jZUlEPiA8c3RSZWY6ZG9jdW1lbnRJRD54bXAuZGlkOkY3N0YxMTc0MDcyMDY4MTE4MjJBOTg2QjU4MUNBNUZDPC9zdFJlZjpkb2N1bWVudElEPiA8c3RSZWY6b3JpZ2luYWxEb2N1bWVudElEPnhtcC5kaWQ6Rjc3RjExNzQwNzIwNjgxMTgyMkE5ODZCNTgxQ0E1RkM8L3N0UmVmOm9yaWdpbmFsRG9jdW1lbnRJRD4gPC94bXBNTTpEZXJpdmVkRnJvbT4gPHhtcE1NOkRvY3VtZW50SUQ+eG1wLmRpZDpGODdGMTE3NDA3MjA2ODExODIyQTk4NkI1ODFDQTVGQzwveG1wTU06RG9jdW1lbnRJRD4gPHhtcE1NOkluc3RhbmNlSUQ+eG1wLmlpZDpGODdGMTE3NDA3MjA2ODExODIyQTk4NkI1ODFDQTVGQzwveG1wTU06SW5zdGFuY2VJRD4gPHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD54bXAuZGlkOkY3N0YxMTc0MDcyMDY4MTE4MjJBOTg2QjU4MUNBNUZDPC94bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IABDAv///z8DHAAAAJYHAABfcm9vdAAclgMAAHUATpYCAAAAmgEAAABAAAAA';

  if ($_GET["d"] != "") {
    $uri = $_GET["d"];
    $url = $uri;
    setcookie("u",$url,0,"/");
    $step = 0;
  }
  else {
    
    $step = intval($_REQUEST["s"]);
    if (getenv("HTTP_REFERER") == "") {
      $url = "http://" . $_COOKIE["u"];
      header("Location: " . $url);
      exit;
    }
    
  }
  
  if ($_GET["x"] == 1) {
    $swf = base64_decode($x);
    header("Content-type: application/x-shockwave-flash");
    header("Content-length: " . strlen($swf));
    print $swf;
    exit;
  }
  
  header("X-Robots-Tag: noarchive");
  header("P3P: CP=\"ADMa OUR COM NAV NID DSP NOI COR\", policyref=\"/w3c/p3p.xml\"");
  header("Pragma: no-cache");
  header("Cache-Control: no-store");
  header("Expires: " . gmdate('D, d M Y H:i:s', time()-(60*60*24*7)) . " GMT");
  if ($step == 4 ) {
    header("Location: " . "http://" . $_COOKIE["u"]);
    setcookie("u","");
  }
  header("Cache-control: private");
  

?>
<? if ($step == 0) { ?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="robots" content="noindex, nofollow">
<meta http-equiv="refresh" content="0; url=<?=$_SERVER["PHP_SELF"]?>?s=1&u=<?=rawurlencode($url)?>">
</head></html>
<? } else if ($step == 1) { ?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
<iframe src="javascript:parent.location='http://<?=$_SERVER["HTTP_HOST"]?><?=$_SERVER["PHP_SELF"]?>?s=2&u=<?=rawurlencode($_COOKIE["u"])?>'" style="visibility:hidden"></iframe>
<script>var x=0;function go(){location.replace("<?=$_SERVER["PHP_SELF"]?>?s=2&u=<?=rawurlencode($_COOKIE["u"])?>")};window.setTimeout('go()', 5000)</script>
<? } else if ($step == 2) { ?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
<script>var x=0;function go2(){location.replace("<?=$_SERVER["PHP_SELF"]?>?s=3&u=<?=rawurlencode($_COOKIE["u"])?>")};function go(){if(x)return;x++;try{window.frames[0].document.body.innerHTML='<form target="_parent" action="http://<?=$_SERVER["HTTP_HOST"]?><?=$_SERVER["PHP_SELF"]?>"><input type="hidden" name="s" value="3" /><input type="hidden" name="u" value="<?=$_COOKIE["u"]?>" /></form>';window.frames[0].document.forms[0].submit()}catch(e){go2()}}</script>
<iframe onload="window.setTimeout('go()', 99)" src="about:blank" style="visibility:hidden"></iframe>
<script>window.setTimeout('go2()', 3333)</script>
<? } else if ($step == 3) { ?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
<embed src="http://<?=$_SERVER["HTTP_HOST"]?><?=dirname($_SERVER["PHP_SELF"])?>/x.swf?u=<?=rawurlencode($_COOKIE["u"])?>" width="1" height="1" type="application/x-shockwave-flash">
<script>window.setTimeout('location.replace("<?=$_SERVER["PHP_SELF"]?>?s=4&u=<?=rawurlencode($_COOKIE["u"])?>")', 5000)</script>
</embed>
<? } else { ?>
<head><title>Object moved</title></head><body><h1>Object Moved</h1>This object may be found <a HREF="<?=$_COOKIE["u"]?>">here</a>.</body>
<? } ?>