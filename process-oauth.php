<?php
include 'credentials/process_oauth_credentials.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


print_r($payload);

$payload_string = http_build_query($payload);
$discord_token_url = "https://discordapp.com/api/oauth2/token";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $discord_token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec($ch);

if(!$result){
    echo curl_error($ch);
}

$result = json_decode($result,true);
$access_token = $result['access_token'];

$discord_users_url = "https://discordapp.com/api/users/@me";
$header = array("Authorization: Bearer $access_token", "Content-Type: application/x-www-form-urlencoded");

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_URL, $discord_users_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec($ch);

$result = json_decode($result, true);

/*
function addUserToGuild($discord_ID,$token,$guild_ID){
    $payload = [
        'access_token'=>$token,
    ];

    $discord_api_url = 'https://discordapp.com/api/guilds/'.$guild_ID.'/members/'.$discord_ID;

    $bot_token = "YOUR BOT TOKEN (SAME AS APPLICATION)";
    $header = array("Authorization: Bot $bot_token", "Content-Type: application/json");

    $ch = curl_init();
    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch,CURLOPT_URL, $discord_api_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //must be put for this method..
    curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($payload)); //must be a json body
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $result = curl_exec($ch);
    
    if(!$result){
        echo curl_error($ch);
    }else{
        return true;
    }
}

function getUsersGuilds($auth_token){
    //url scheme /users/@me/guilds
    $discord_api_url = "https://discordapp.com/api";
    $header = array("Authorization: Bearer $auth_token","Content-Type: application/x-www-form-urlencoded");
    $ch = curl_init();
    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch,CURLOPT_URL, $discord_api_url.'/users/@me/guilds');
    curl_setopt($ch,CURLOPT_POST, false);
    //curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    $result = json_decode($result,true);
    return $result;
}*/



// Get multiple guild roles
function getUsersGuildsRoles($discord_ID,$auth_token,$guild_IDs){
	$final_result = array();
	$final_result["roles"] = array();
	foreach($guild_IDs AS $index => $guild_ID){
	    //url scheme /users/@me/guilds
	    $discord_api_url = "https://discordapp.com/api/users/@me/guilds/".$guild_ID."/member";
	    $header = array("Authorization: Bearer $auth_token","Content-Type: application/x-www-form-urlencoded");
	    $ch = curl_init();
	    //set the url, number of POST vars, POST data

	    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
	    curl_setopt($ch,CURLOPT_URL, $discord_api_url); // /guilds.$guild_ID.'/members/'.$discord_ID
	    curl_setopt($ch,CURLOPT_POST, false);
	    //curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    $result = curl_exec($ch);
		
		$result = json_decode($result,true);
		if(isset($result['roles'])){
	    	$final_result['roles'] = array_merge($result['roles'], $final_result['roles']);
		}
	}
    return $final_result['roles'];
}

// Get single guild role
function getUsersGuildRoles($discord_ID,$auth_token,$guild_ID){
    //url scheme /users/@me/guilds
    $discord_api_url = "https://discordapp.com/api/users/@me/guilds/".$guild_ID."/member";
    $header = array("Authorization: Bearer $auth_token","Content-Type: application/x-www-form-urlencoded");
    $ch = curl_init();
    //set the url, number of POST vars, POST data

    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch,CURLOPT_URL, $discord_api_url); // /guilds.$guild_ID.'/members/'.$discord_ID
    curl_setopt($ch,CURLOPT_POST, false);
    //curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);

    $result = json_decode($result,true);
    return $result['roles'];
}

session_start();

$_SESSION['logged_in'] = true;
$_SESSION['userData'] = [
    'name'=>$result['username'],
    'discord_id'=>$result['id'],
    'avatar'=>$result['avatar'],
	// Get single guild role
    //'roles'=>getUsersGuildRoles($result['id'],$access_token,$guild_ID)
	// Get multiple guild roles
	'roles'=>getUsersGuildsRoles($result['id'],$access_token,$guild_IDs)
/*	'guilds'=>getUsersGuilds($access_token)*/
];

header("location: dashboard.php");
exit();
