<?php // res.php?key=YOUR_API_KEY&id=CAPTCHA_ID //TODO:&json=1

if(!isset($_GET['key']) || !ctype_alnum($_GET['key'])) 			exit("ERROR_WRONG_USER_KEY");//|| strlen($_GET['key']) != 32
if(!isset($_GET['id']) || !ctype_alnum($_GET['id'])) 			exit("ERROR_WRONG_ID_FORMAT");//|| strlen($_GET['id']) != 8

	include_once("confignconnect.php");

	$dbh = $db->prepare("SELECT a_answer FROM ks_solve WHERE a_reqkey = :id AND a_api = :key ORDER BY a_index DESC LIMIT 1");
	$dbh->bindParam(':id', $_GET['id'], PDO::PARAM_INT, 10);
	$dbh->bindParam(':key', $_GET['key'], PDO::PARAM_STR, 40);
	$dbh->execute();
	if($dbh->rowCount() != 1) 					exit("ERROR_WRONG_CAPTCHA_ID");
	$res = $dbh->fetch();
	if(empty($res['a_answer']))					exit("CAPCHA_NOT_READY");
	
	echo "OK|".$res['a_answer'];
?>