<?php
include 'db.php';
include 'webhooks.php';

if(isset($argv)){
	parse_str(implode('&', array_slice($argv, 1)), $_GET);
}
// Validate cron job execution
if(isset($_GET['automate'])){	
	automate($conn, 1);
	automate($conn, 4);
}

function automate($conn, $project_id){
	if($project_id == 1){
		$prefix = "drop-ship";
		$prizes = "1K, 500, 250 DREAD";
	}else if($project_id == 4){
		$prefix = "oculus-lounge";
		$prizes = "1K, 500, 250 MOON";
	}
	$_SESSION['userData']['project_id'] = $project_id;
	
	// Automatically end current game
	checkGame($conn);
	$title = "Game Over";
	ob_start(); // Start output buffering
	checkLeaderboard($conn, "true");
	$list = ob_get_contents(); // Store buffer in variable
	ob_end_clean(); // End buffering and clean up
	$description = $list;
	$imageurl = "https://www.madballs.net/".$prefix."/images/dropship.jpg";
	discordmsg($title, evaluateText($description), $imageurl, $_SESSION['userData']['project_id']);
	deactivateGame($conn);

	// Automatically create new game
	createGame($conn, $prizes, 3);
	$title = "New Game ".$prizes;
	$description = "A new game of ".getProjectName($conn)." has been created.";
	$imageurl = "https://www.madballs.net/".$prefix."/images/dropship.jpg";
	discordmsg($title, $description, $imageurl, $_SESSION['userData']['project_id']);
}
?>