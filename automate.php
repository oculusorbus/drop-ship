<?php
include 'db.php';
include 'webhooks.php';

if(isset($argv)){
	parse_str(implode('&', array_slice($argv, 1)), $_GET);
}
// Validate cron job execution
if(isset($_GET['automate'])){	
	$_SESSION['userData']['project_id'] = 1;
	// Automatically end current game
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
	createGame($conn, "1K, 500, 250 DREAD", 3);
	$title = "New Game 1K, 500, 250 DREAD";
	$description = "A new game of ".getProjectName($conn)." has been created.";
	$imageurl = "https://www.madballs.net/".$prefix."/images/dropship.jpg";
	discordmsg($title, $description, $imageurl, $_SESSION['userData']['project_id']);
}
?>