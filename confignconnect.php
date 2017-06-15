<?php
	error_reporting(0);
	date_default_timezone_set('Europe/Berlin');
	try{$db = new PDO("mysql:host=127.0.0.1;dbname=karmel", "root", "");}
	catch(PDOException $e){exit("ERROR:2003");}
?>