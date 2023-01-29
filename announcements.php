<?php
include 'db.php';
include 'webhooks.php';
//include 'dropship.php';

announceBattleResults($_GET["type"], $_GET["user_id"], $_GET["battle_id"]);

// Announce battle results
function announceBattleResults($type, $user_id, $battle_id){
	global $prefix, $avatar_url, $conn;
	$wager = getWager($conn, $battle_id);
	$opponent = getOpponentUsername($conn, $battle_id);
	$creator = getCreatorUsername($conn, $battle_id);
	
	$title = "PvP ".evaluateText("Battle").": Dead on Round ".$_SESSION['userData']['score'];
	// Disabling inventory list for battles because it's tied to results and game id
	//ob_start(); // Start output buffering
	//checkPlayerItems($conn);
	//$list = ob_get_contents(); // Store buffer in variable
	//ob_end_clean(); // End buffering and clean up
	
	// Append this to description variable if you get it working
	// "\n".evaluateText($list)
	if($type == "opponent"){
		$description = $_SESSION['userData']['name']." died during Round ".$_SESSION['userData']['score']." in battle with ".$creator." for ".$wager." $".evaluateText("SCRIP").". It is now ".$creator."'s turn to defend.";
	}else if($type == "creator"){
		$opponent_score = getOpponentScore($conn, $battle_id);
		$battle_markup = "";
		if($_SESSION['userData']['score'] > $opponent_score){
			$title = "WINNER - ".$title;
			$battle_markup = " and won ".$wager." $".evaluateText("SCRIP")." against score of ".$opponent_score;
		}else if($_SESSION['userData']['score'] < $opponent_score){
			$title = "LOSER - ".$title;
			$battle_markup = " and lost ".$wager." $".evaluateText("SCRIP")." against score of ".$opponent_score;
		}else if($_SESSION['userData']['score'] == $opponent_score){
			$title = "TIE - ".$title;
			$battle_markup = " and kept ".$wager." $".evaluateText("SCRIP")." by tying with score of ".$opponent_score;
		}
		$description = $_SESSION['userData']['name']." died during Round ".$_SESSION['userData']['score'].$battle_markup." by ".$opponent;
	}
	$imageurl = $avatar_url;
	discordmsg($title, $description, $imageurl, "https://madballs.net/drop-ship/battles.php");
}

?>