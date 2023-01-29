<?php
//include 'db.php';
//$_SESSION['userData']['project_id'] = $_GET["project_id"];
$prefix = $_GET["prefix"];
include 'webhooks.php';

$type = $_GET["type"];
$user_id = $_GET["user_id"];
$battle_id = $_GET["battle_id"];
$name = $_GET["name"];
$score = $_GET["score"];
$avatar_url = $_GET["avatar_url"];
$project_id = $_GET["project_id"];
$wager = $_GET["wager"];
$opponent = $_GET["opponent"];
$creator = $_GET["creator"];
$opponent_score = $_GET["opponent_score"];
$battle = $_GET["battle"];
$scrip = $_GET["scrip"];

sleep($score*2);

announceBattleResults($type, $user_id, $battle_id, $name, $score, $avatar_url, $project_id, $wager, $opponent, $creator, $opponent_score, $battle, $srip);

// Announce battle results
function announceBattleResults($type, $user_id, $battle_id, $name, $score, $avatar_url, $project_id, $wager, $opponent, $creator, $opponent_score, $battle, $srip){
	//global $conn;
	
	$title = "PvP ".$battle.": Dead on Round ".$score;
	// Disabling inventory list for battles because it's tied to results and game id
	//ob_start(); // Start output buffering
	//checkPlayerItems($conn);
	//$list = ob_get_contents(); // Store buffer in variable
	//ob_end_clean(); // End buffering and clean up
	
	// Append this to description variable if you get it working
	// "\n".evaluateText($list)
	
	if($type == "opponent"){
		$description = $name." died during Round ".$score." in battle with ".$creator." for ".$wager." $".$scrip.". It is now ".$creator."'s turn to defend.";
	}else if($type == "creator"){
		$battle_markup = "";
		if($score > $opponent_score){
			$title = "WINNER - ".$title;
			$battle_markup = " and won ".$wager." $".$scrip." against score of ".$opponent_score;
		}else if($score < $opponent_score){
			$title = "LOSER - ".$title;
			$battle_markup = " and lost ".$wager." $".$scrip." against score of ".$opponent_score;
		}else if($score == $opponent_score){
			$title = "TIE - ".$title;
			$battle_markup = " and kept ".$wager." $".$scrip." by tying with score of ".$opponent_score;
		}
		$description = $name." died during Round ".$score.$battle_markup." by ".$opponent;
	}
	$imageurl = $avatar_url;
	discordmsg($title, $description, $imageurl, $project_id, "https://madballs.net/drop-ship/battles.php");
}
?>