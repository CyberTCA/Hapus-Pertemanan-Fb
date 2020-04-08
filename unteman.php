<?php
set_time_limit(0);
require_once('color.php');

function friendlist($token){
	$a = json_decode(file_get_contents('https://graph.facebook.com/me/friends?access_token='.$token), true);
	return $a['data'];
}
function last_active($id, $tok){
	$a = json_decode(file_get_contents('https://graph.facebook.com/'.$id.'/feed?access_token='.$tok.'&limit=1'), true);
	$date = $a['data'][0]['created_time'];
	$aa = strtotime($date);
	return date('Y', $aa);
}

function unfriend($id, $token){
	$url = 'https://graph.facebook.com/me/friends?uid='.$id.'&access_token='.$token;
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
	if($result == true){
		$unf = Console::green('[UNFRIENDED]');
	} else {
		$unf = Console::red('[FAILED TO UNFRIEND]');
	}
	return $unf;
}

echo Console::blue("     \033[34m╔════════════════════════════════╗\n");
echo Console::blue("       \033[31m⏣ \033[32mAuthor   \033[37m: Dimas Gans\n\n");
echo Console::blue("       \033[31m⏣ \033[32mMy Team  \033[37m: Program Jalanan\n");
echo Console::blue("       \033[31m⏣ \033[32mwhatsapp \033[37m: 083813844572\n");
echo Console::blue("       \033[31m⏣ \033[32mCodeName \033[37m: \033[36mMr.Tcg_Cyber\n");
echo Console::blue("     \033[34m╚════════════════════════════════╝\n");

//INPUT
echo "Facebook token : ";
$fbtoken = trim(fgets(STDIN));
echo "Tahun Pertemanan Fb Lo : ";
$year = trim(fgets(STDIN));
echo "\n";

$count = 0;
$FL = friendlist($fbtoken);
$totalFL = count($FL);
foreach($FL as $list){
	$count++;
	$name = $list['name'];
	$id = $list['id'];
	$date = last_active($id, $fbtoken);
	echo Console::cyan("(" .$count. "/" .$totalFL. ")");
	if($date < $year){
		echo Console::red('[INACTIVE]').' '.$name.' ~ '.$date.' '.unfriend($id, $fbtoken);
		echo "\r\n";
	}else{
		echo Console::green('[ACTIVE]').' '.$name.' ~ '.$date;
		echo "\r\n";
	}
}
