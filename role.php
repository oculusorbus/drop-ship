<?php
function assignRole($discord_id, $role_id) {
	global $bot_token;
	$authToken = $bot_token;
	$guildid = "966397496978964500";
	$userid = $discord_id;
	$roleid = $role_id;
	$url = "https://discordapp.com/api/v6/guilds/" . $guildid . "/members/" . $userid . "/roles/" . $roleid;

	$ch = curl_init();
	curl_setopt_array($ch, array(
	    CURLOPT_URL            => $url,
	    CURLOPT_HTTPHEADER     => array(
	        'Authorization: Bot '.$authToken,
	        "Content-Length: 0"
	    ),
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_CUSTOMREQUEST  => "PUT",
	    CURLOPT_FOLLOWLOCATION => 1,
	    CURLOPT_VERBOSE        => 1,
	    CURLOPT_SSL_VERIFYPEER => 0
	));
	$response = curl_exec($ch);

	//It's possible to output the response at this place for debugging, so remove the comment if needed

	print $response;
	print "<pre>";
	print_r(json_decode($response));
	print "</pre>";

	curl_close($ch);
}
?>