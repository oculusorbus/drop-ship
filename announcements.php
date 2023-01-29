<?php
include 'db.php';
$_SESSION['userData']['project_id'] = $_POST["project_id"]; 
include 'webhooks.php';
//include 'dropship.php';
sleep(60);
announceBattleResults($_POST["type"], $_POST["user_id"], $_POST["battle_id"], $_POST["name"], $_POST["score"], $_POST["prefix"], $_POST["avatar_url"]);

// Announce battle results
function announceBattleResults($type, $user_id, $battle_id, $name, $score, $prefix, $avatar_url){
	global $conn;
	$wager = getWager($conn, $battle_id);
	$opponent = getOpponentUsername($conn, $battle_id);
	$creator = getCreatorUsername($conn, $battle_id);
	
	$title = "PvP ".evaluateText("Battle").": Dead on Round ".$score;
	// Disabling inventory list for battles because it's tied to results and game id
	//ob_start(); // Start output buffering
	//checkPlayerItems($conn);
	//$list = ob_get_contents(); // Store buffer in variable
	//ob_end_clean(); // End buffering and clean up
	
	// Append this to description variable if you get it working
	// "\n".evaluateText($list)
	if($type == "opponent"){
		$description = $name." died during Round ".$score." in battle with ".$creator." for ".$wager." $".evaluateText("SCRIP").". It is now ".$creator."'s turn to defend.";
	}else if($type == "creator"){
		$opponent_score = getOpponentScore($conn, $battle_id);
		$battle_markup = "";
		if($score > $opponent_score){
			$title = "WINNER - ".$title;
			$battle_markup = " and won ".$wager." $".evaluateText("SCRIP")." against score of ".$opponent_score;
		}else if($score < $opponent_score){
			$title = "LOSER - ".$title;
			$battle_markup = " and lost ".$wager." $".evaluateText("SCRIP")." against score of ".$opponent_score;
		}else if($score == $opponent_score){
			$title = "TIE - ".$title;
			$battle_markup = " and kept ".$wager." $".evaluateText("SCRIP")." by tying with score of ".$opponent_score;
		}
		$description = $name." died during Round ".$score.$battle_markup." by ".$opponent;
	}
	$imageurl = $avatar_url;
	discordmsg($title, $description, $imageurl, "https://madballs.net/drop-ship/battles.php");
}

?>