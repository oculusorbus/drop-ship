<?php
include 'db.php';
$_SESSION['userData']['project_id'] = $_GET["project_id"];
$prefix = $_GET["prefix"];
include 'webhooks.php';
//include 'dropship.php';
//sleep(60);


$type = $_GET["type"];
$user_id = $_GET["user_id"];
$battle_id = $_GET["battle_id"];
$name = $_GET["name"];
$score = $_GET["score"];
$prefix = $_GET["prefix"];
$avatar_url = $_GET["avatar_url"];


//announceBattleResults($type, $user_id, $battle_id, $name, $score, $prefix, $avatar_url);

// Announce battle results
function announceBattleResults($type, $user_id, $battle_id, $name, $score, $prefix, $avatar_url){

}
?>