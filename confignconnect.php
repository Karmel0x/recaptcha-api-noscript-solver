<?php
	error_reporting(0);
	date_default_timezone_set('Europe/Berlin');
	try{$db = new PDO("mysql:host=127.0.0.1;dbname=lolcance_license", "lolcance_krmlk", "KrmlK123");}
	//try{$db = new PDO("mysql:host=127.0.0.1;dbname=krml_lolchecker", "root", "");}
	catch(PDOException $e){exit("ERROR:2003");}
?>