<?php
include 'db.php';
include 'dropship.php';
if(isset($_POST['GET']['ath'])) {
	checkATHLeaderboard($conn, true);
} else if (isset($_POST['GET']['xp'])){
	checkXPLeaderboard($conn, true);
} else {
	checkLeaderboard($conn, true);
}
// Close DB Connection
$conn->close();
?>