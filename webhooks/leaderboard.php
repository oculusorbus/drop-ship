<?php
include '../db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

$_SESSION['userData'] = array();

if($_GET['project'] == "Drop Ship"){
	$_SESSION['userData']['project_id'] = 1;
}else if($_GET['project'] == "Dread City"){
	$_SESSION['userData']['project_id'] = 2;
}else if($_GET['project'] == "Filthy Mermaid"){
	$_SESSION['userData']['project_id'] = 3;
}else if($_GET['project'] == "Oculus Lounge"){
	$_SESSION['userData']['project_id'] = 4;
}else{
	$_SESSION['userData']['project_id'] = 1;
}

if(isset($_GET['ath'])){
	checkATHLeaderboard($conn, true);
}else if(isset($_GET['xp'])){
	checkXPLeaderboard($conn, true);
}else{
	checkGame($conn);
	if(isset($_SESSION['userData']['game_id'])) {
		checkLeaderboard($conn, true);
	} else {
		echo "Drop Ship is not active.";
	}
}

// Close DB Connection
$conn->close();
?>