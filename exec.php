<?php
// MIT License :: Copyright (c) 2017 Karmel0x
// https://github.com/Karmel0x/recaptcha-api-noscript-solver
//for windows - change "/dev/null" to "nul"
//TODO: improve speech recognition (sr/0.dict)
//TODO: ERROR_YOUR_IP_IS_BANNED_BY_GOOGLE

	include_once("confignconnect.php");
	if(file_exists("t1"))exit();
    fclose(fopen('t1', 'w'));
	for(;;){$repeat=0;//make sure that all queries will be queued
		$dbh1 = $db->prepare("DELETE FROM ks_solve WHERE a_solvetime < ".time()-3600);
		$dbh1->execute();
		$dbh1 = $db->prepare("SELECT * FROM ks_solve WHERE a_solvetime = 0");
		$dbh1->execute();
		function get_string_between($string, $start, $end){
			$string = ' '.$string;
			$ini = strpos($string, $start);
			if ($ini === false) return '';
			$ini += strlen($start);
			$len = strpos($string, $end, $ini) - $ini;
			return substr($string, $ini, $len);
		}
		while($row = $dbh1->fetch()){$repeat=1;
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:53.0) Gecko/20100101 Firefox/53.0");
			curl_setopt($curl, CURLOPT_TIMEOUT, 4);		 //reconnect timeout
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 4);//connect timeout
			curl_setopt($curl, CURLOPT_LOW_SPEED_TIME, 4);//receiving data timeout
			if(!empty($row['a_ref'])) curl_setopt($curl, CURLOPT_REFERER, $row['a_ref']);
			
			curl_setopt($curl, CURLOPT_URL, "http://google.com/recaptcha/api/noscript?k=".$row['a_googlekey']."&is_audio=true");
			for($i=1;$i<9;$i++) if($response = curl_exec($curl)) break;else sleep(1);if($i>8) exit("ERROR:2002");//TODO:error_reporting
			
			curl_setopt($curl, CURLOPT_POST, 1);
			for($j=1;$j<16;$j++){
				if('' == ($rcf = get_string_between($response, 'recaptcha_challenge_field', '>'))){//wrong googlekey
					$dbh = $db->prepare("UPDATE ks_solve SET a_solvetime = '2', a_answer = 'ERROR_CAPTCHA_UNSOLVABLE' WHERE a_reqkey = '".$row['a_reqkey']."'");
					$dbh->execute();continue 2;}
				$input1 = get_string_between($rcf, 'value="', '"');

				$mp3l = get_string_between($response, 'image?', '"');
				file_put_contents("sr/af/i".$row['a_reqkey'].".mp3", file_get_contents("http://google.com/recaptcha/api/image?".$mp3l));

				exec("sr/ffmpeg -y -i sr/af/i".$row['a_reqkey'].".mp3 -ar 8000 -ac 1 sr/af/i".$row['a_reqkey'].".wav");//TODO:lighter lib
				$input2 = shell_exec("export LD_LIBRARY_PATH=sr/unix64 ; sr/unix64/pocketsphinx_continuous -infile sr/af/i".$row['a_reqkey'].".wav -hmm sr/en-us-ptm-8khz -dict sr/0.dict -jsgf sr/0.jsgf -logfn /dev/null -vad_prespeech 1 -vad_postspeech 25 -remove_dc yes -remove_noise yes -vad_threshold 3.4 -wip 1e-5 -samprate 8000 2>&1");
				//			(linux)	'export LD_LIBRARY_PATH=sr/unix64 ; "'
				curl_setopt($curl, CURLOPT_POSTFIELDS, "recaptcha_challenge_field=$input1&recaptcha_response_field=$input2&submit=Jestem+cz³owiekiem");
				for($i=1;$i<9;$i++) if($response = curl_exec($curl)) break;else sleep(1);if($i>8) exit("ERROR:2002");//TODO:error_reporting

				$post1a = get_string_between($response, '<textarea rows="5" cols="100">', '</textarea>');
				if($post1a != '') break;sleep(1);
			}if($j>15){
				$dbh = $db->prepare("UPDATE ks_solve SET a_solvetime = 1, a_answer = 'ERROR_BAD_DUPLICATES' WHERE a_reqkey = '".$row['a_reqkey']."'");
				$dbh->execute();continue;//exit("ERROR:3");
			}

			curl_close($curl);
			$dbh = $db->prepare("UPDATE ks_solve SET a_solvetime = '".time()."', a_answer = '".$post1a."' WHERE a_reqkey = '".$row['a_reqkey']."'");
			$dbh->execute();
			$dbh = $db->prepare("UPDATE ks_users SET a_credits = a_credits - 1 WHERE a_api = '".$row['a_api']."'");
			$dbh->execute();
			unlink("sr/af/i".$row['a_reqkey'].".mp3");unlink("sr/af/i".$row['a_reqkey'].".wav");//TODO:pingback
		}if($repeat != 1) break;
	}unlink("t1");
	?>