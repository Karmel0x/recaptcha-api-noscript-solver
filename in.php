<?php // in.php?key=YOUR_API_KEY&googlekey=googlekey&pageurl=site.com //for windows - change "/dev/null" to "nul"

if(!isset($_GET['key']) || !ctype_alnum($_GET['key'])) 				exit("ERROR_WRONG_USER_KEY");//|| strlen($_GET['key']) != 32
if(!isset($_GET['googlekey']) || !ctype_alnum($_GET['googlekey'])) 	exit("ERROR:1");
if(!isset($_GET['pageurl'])) $_GET['pageurl'] = "";// referer?

	include_once("confignconnect.php");
	
	$dbh = $db->prepare("SELECT * FROM ks_users WHERE a_api = :key");
	$dbh->bindParam(':key', $_GET['key'], PDO::PARAM_STR, 40);
	$dbh->execute();
	if($dbh->rowCount() != 1) 					exit("ERROR_KEY_DOES_NOT_EXIST");
	$res = $dbh->fetch();
	if($res['a_credits'] < 1) 					exit("ERROR_ZERO_BALANCE");
	
	$reqkey = substr(time(), 6).rand(1000, 9999);
	$dbh = $db->prepare("INSERT INTO ks_solve (a_reqkey, a_requesttime, a_googlekey, a_api, a_ref) VALUES('$reqkey', '".time()."', :googlekey, :key, :pageurl)");
	$dbh->bindParam(':googlekey', $_GET['googlekey'], PDO::PARAM_STR, 99);
	$dbh->bindParam(':key', $_GET['key'], PDO::PARAM_STR, 40);
	$dbh->bindParam(':pageurl', $_GET['pageurl'], PDO::PARAM_STR, 99);
	$dbh->execute();
	
	if(!file_exists("t1"))
		exec('php exec.php > /dev/null 2>/dev/null &');
	
	echo "OK|".$reqkey;
	?>