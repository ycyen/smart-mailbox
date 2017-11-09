<?php
error_reporting(-1);
ini_set('display_errors', 'On');
echo "
<head>
  <meta charset=\"UTF-8\">
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1\">
  <link rel=\"shortcut icon\" href=\"./favicon.ico\">
</head>
<body>";

	if(isset($_GET['time'])){
		$time = $_GET['time'];
		$user = $_GET['user'];
		$pattern = '/^\d{10}$/';
		$pattern2 = '/^[A-Za-z0-9]{11}$/';
		if(preg_match($pattern, $time)==1&&preg_match($pattern2, $user)==1){
			file_put_contents("./log/".$user, $time."\n", FILE_APPEND);
			$iden = shell_exec("/usr/lib/cgi-bin/query.sh ".$user.' 0');
			echo $iden;
			shell_exec("/usr/lib/cgi-bin/alert.sh ".$time." ".$iden);
			echo "<h1>Success</h1>";
		}
		else echo "<h1>Failure</h1>";
	}
echo "
</body>	
"
?>
