<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
echo "
<head>
  <meta charset=\"UTF-8\">
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1\">
  <title>Welcome to You've Got A Mail</title>
  <link rel=\"shortcut icon\" href=\"../favicon.ico\">
	<script src=\"http://code.jquery.com/jquery-2.1.3.min.js\" type=\"text/javascript\"></script>
<script>
function  SetCookie(name,  value)
{
var  expdate  =  new  Date();
var  argv  =  SetCookie.arguments;
var  argc  =  SetCookie.arguments.length;
var  expires  =  (argc  >  2)  ?  argv[2]  :  null;
var  path  =  (argc  >  3)  ?  argv[3]  :  null;
var  domain  =  (argc  >  4)  ?  argv[4]  :  null;
var  secure  =  (argc  >  5)  ?  argv[5]  :  false;
if(expires!=null)  expdate.setTime(expdate.getTime()  +  (  expires  *  1000  ));
document.cookie  =  name  +  \"=\"  +  escape  (value)  +((expires  ==  null)  ?  \"\"  :  (\";  expires=\"+  expdate.toGMTString()))
+((path  ==  null)  ?  \"\"  :  (\";  path=\"  +  path))  +((domain  ==  null)  ?  \"\"  :  (\";  domain=\"  +  domain))
+((secure  ==  true)  ?  \";  secure\"  :  \"\");
}
function  GetCookie(name)
{
  var cookies=document.cookie.replace(/ /g, '').split(';');
  for( var i=0; i<cookies.length; i++){
    var cookie = cookies[i].split('=');
    //console.log(cookie);
    if(cookie[0].length!=1)continue;
    else if(cookie[0]==name)return cookie[1];
  }
  return null;
}

</script>
<style>
body{
				text-align:center;
				font-family: 微軟正黑體;
			}
			img{
				max-width: 80%;
			}
			div#header{
				text-align:left;
				margin: 0px auto;
				padding: 0;
				border: 0;	
				width: 100%;
				height: 90px;
				background-color: DarkGreen;
			}
			div#header p{
				margin: 35px 0px 0px 0px;
				padding:0px;
				position:absolute;
				font-family: fantasy;
				font-size:xx-large;
				color:white;
			}
			.mailtable .newmail{
				width:280px;
				height:50px;
				background-color:Gold;
				border:1.5px solid;
				border-radius:17px;
			}
			.mailtable .oldmail{
				width:280px;
				height:50px;
				background-color:#efefef;
				border:1.5px solid;
				border-radius:17px;
			}


	</style>
</head>
<body>
<center><img src=\"./favicon.gif\"></img></center>";
if(isset($_POST['iden'])){
	$iden=substr($_POST['iden'], 0,11);
		echo "
<form style=\"display:\" action=\"./index.php\" method=\"POST\" id=\"hform\">
<input type=\"hidden\" id=\"iden\" name=\"iden\" value=\"".$iden."\">
<input type=\"submit\" value=\"Refresh\">
</form>";
	goto gaaa;
}
//		error_reporting(1);
//		ini_set('display_errors',1); 
if(isset($_GET['code'])){
	$url="https://api.pushbullet.com/oauth2/token";
	$ch = curl_init();
	$para = array('grant_type' => 'authorization_code', 'client_id' => 'ZUBQAIsqBej2w8lbSZvZCjQkV2wYfwEl', 'client_secret' => 'E9yWgbgCE2zIeBZzrqTGdNS7mPgRwxBI', 'code' => $_GET['code']);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $para);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$token = curl_exec($ch);
	if(strpos($token, 'invalid')!=false){
		echo "<h2>Authorization failed.</h2>";
	}
	else{
		$token = substr($token, strpos($token, '_token\":\"o')+17, strpos($token, ',\"token_type')-strpos($token, '_token\":\"o')-24);
	  $out=shell_exec('/usr/lib/cgi-bin/login.sh 0 \''.$token.'\'');
		$iden=substr(shell_exec('/usr/lib/cgi-bin/login.sh 1 \''.$token.'\''), 0, 11);
		echo "
<script>
 SetCookie('u','".$iden."', 604800)
</script>";
		echo "
<form style=\"display:\" action=\"./index.php\" method=\"POST\" id=\"hform\">
<input type=\"hidden\" id=\"iden\" name=\"iden\" value=\"".$iden."\">
<input type=\"submit\" value=\"Refresh\">
</form>";
		echo $out;
		$iden = trim(preg_replace('/\s+/', ' ', $iden));
		gaaa:
		$logfile = file("/var/www/log/".$iden);
		$logfile = array_reverse($logfile);
		$time = file_get_contents("./time/".$iden);
		$curtime = shell_exec('date +%s');
		file_put_contents("./time/".$iden, $curtime);
		//echo $time;
		//echo "rr";
		//echo $curtime;
		$new=0;
		foreach($logfile as $line){
			if(intval($line)>$time)$new++;
		}

		echo "
<div id=\"header\">
			<p>You've got <span>".$new."</span> new mails</p>
			<!--<a href=\"test.html\"><img src=\"refresh.png\"/ style=\"margin:20px;width:50px; height:50px; float:right\"></a>-->
		</div>
		<div>
			<table class=\"mailtable\" align=\"center\">


			";
		
		
		if(count($logfile)){
			foreach($logfile as $line){
				if(intval($line)>$time)echo "<tr><td><div class=\"newmail\"><p>".date('Y/m/d H:i:s',intval($line))."</p></div></td></tr>";
				else echo "<tr><td><div class=\"oldmail\"><p>".date('Y/m/d H:i:s',intval($line))."</p></div></td></tr>";
			}
		}
		echo "
				
			</table>
		</div>
			
";
	}
}
else if(isset($_GET['error'])){
		echo "Authorization cancelled.";
}
else{
echo "
<form style=\"display:none\" action=\"./index.php\" method=\"POST\" id=\"hform\">
<input type=\"hidden\" id=\"iden\" name=\"iden\" value=\"\">
<input type=\"submit\" value=\"Refresh\">
</form>
	<script>
$(function(){
var iden = GetCookie('u');
console.log(iden);
if(iden!=null){
	$('#iden').val(iden);
	$('#hform').submit();
}

})
</script>
<form action=\"https://www.pushbullet.com/authorize\" method=\"get\"><p style=\"white-space: pre-wrap;font-size:16pt;\"><input type=\"hidden\" name=\"client_id\" value=\"ZUBQAIsqBej2w8lbSZvZCjQkV2wYfwEl\"><input type=\"hidden\" name=\"redirect_uri\" value=\"http://youvegotamail.csie.org/index.php\"><input type=\"hidden\" name=\"response_type\" value=\"code\"><input type=\"submit\" value=\"Login\">
</form>
";
}
echo"
</body>";
