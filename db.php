<?php
include 'credentials/db_credentials.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);  


if(isset($_SESSION['userData']['discord_id'])){
	if($_SESSION['userData']['discord_id'] == $discordid_oculusorbus) {
		$dbname = $dbbametest;
	}
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function evaluateText($text){
	// Moving inside function because session doesn't exist until after text evaluation is called
	$replacements = array();
	$replacements_description = array();
	if(isset($_SESSION['userData']['project_id'])){
		if($_SESSION['userData']['project_id'] == 2){
			$replacements['SCRIP'] = 'DREAD';
			$replacements['Barracks'] = 'Hideout';
			
			$replacements_description['beach'] = 'downtown';
			$replacements_description['hill'] = 'complex';
			$replacements_description['bunker'] = 'hideout';			

			$replacements['Base'] = 'Leather Jacket';
			$replacements['Light'] = 'Bulletproof Vest';
			$replacements['Medium'] = 'Steel Armor';
			$replacements['Heavy'] = 'Titanium Armor';
			$replacements['Base Armor'] = 'Leather Jacket';
			$replacements['Light Armor'] = 'Bulletproof Vest';
			$replacements['Medium Armor'] = 'Steel Armor';
			$replacements['Heavy Armor'] = 'Titanium Armor';
			$replacements['Tactical Katana'] = 'Machete';
			$replacements['Quantum Stealth'] = 'Mask & Hoodie';
			$replacements['Exo Suit'] = 'SUV';
			$replacements['Mech Suit'] = 'Hummer';
			$replacements['Night Vision Goggles'] = 'Flashlight';
			$replacements['Radar'] = 'GPS Tracker';
			$replacements['Radio'] = 'Walkie Talkie';
			$replacements['Jet Pack'] = 'Helicopter';
			$replacements['Drop Box'] = 'Loot Crate';

			$replacements['tactical-katana'] = 'machete';
			$replacements['base-armor'] = 'leather-jacket';
			$replacements['light-armor'] = 'bulletproof-vest';
			$replacements['medium-armor'] = 'steel-armor';
			$replacements['heavy-armor'] = 'titanium-armor';
			$replacements['quantum-stealth'] = 'hoodie';
			$replacements['exo-suit'] = 'suv';
			$replacements['mech-suit'] = 'hummer';
			$replacements['night-vision-goggles'] = 'flashlight';
			$replacements['radar'] = 'gps';
			$replacements['jet-pack'] = 'helicopter';
			$replacements['drop-box'] = 'crate';
		} else if($_SESSION['userData']['project_id'] == 3 || $_SESSION['userData']['project_id'] == 4){
			if($_SESSION['userData']['project_id'] == 3){
				$replacements['Drop Ship'] = 'Filthy Mermaid';
				$replacements['Drop Ship Initiating'] = 'Filthy Mermaid Entrance';
				$replacements['Drop Ship Landed'] = 'Filthy Mermaid Doorway';
				$replacements['drop-ship'] = 'filthy-mermaid';
			}else{
				$replacements['Drop Ship'] = 'Oculus Lounge';
				$replacements['Drop Ship Initiating'] = 'Oculus Lounge Entrance';
				$replacements['Drop Ship Landed'] = 'Oculus Lounge Hallway';
				$replacements['drop-ship'] = 'oculus-lounge';
				$replacements['Soldiers'] = 'Citizens';
				$replacements['Registered Soldiers'] = 'Oculus Lounge VIPs';
				$replacements['Beach Secured'] = 'Approach Stage';
				$replacements['Hill Secured'] = 'Enter VIP';
			}
			
			$replacements['SCRIP'] = 'TIDDIES';
			if($_SESSION['userData']['project_id'] == 3){
				$replacements['Barracks'] = 'Hideout';
				$replacements['Armory'] = 'Sex Shop';
			}else{
				$replacements['Barracks'] = 'Moebius-9 Dome';
				$replacements['Armory'] = 'Sex Shop';
			}
			
			$replacements_description['Stormed the Beach'] = 'Entered the Lobby';
			$replacements_description['Climbed the Hill'] = 'Approached the Stage';
			$replacements_description['Entered the Bunker'] = 'Accessed VIP';
			

			$replacements_description['beach'] = 'lobby';
			$replacements_description['hill'] = 'stage';
			$replacements_description['bunker'] = 'VIP';

			$replacements['Base'] = 'Boxer Briefs';
			$replacements['Light'] = 'Basketball Shorts';
			$replacements['Medium'] = 'Sweat Pants';
			$replacements['Heavy'] = 'Smoking Jacket';
			$replacements['Base Armor'] = 'Boxer Briefs';
			$replacements['Light Armor'] = 'Basketball Shorts';
			$replacements['Medium Armor'] = 'Sweat Pants';
			$replacements['Heavy Armor'] = 'Smoking Jacket';
			$replacements['Ballistic Shield'] = 'Fur Coat';
			$replacements['Quantum Stealth'] = 'Pimp Suit';
			$replacements['Exo Suit'] = 'Trench Coat';
			$replacements['Mech Suit'] = 'Birthday Suit';
			
			$replacements_description['Base Armor'] = 'Boxer Briefs';
			$replacements_description['Light Armor'] = 'Basketball Shorts';
			$replacements_description['Medium Armor'] = 'Sweat Pants';
			$replacements_description['Heavy Armor'] = 'Smoking Jacket';
			$replacements_description['Ballistic Shield'] = 'Fur Coat';
			$replacements_description['Quantum Stealth'] = 'Pimp Suit';
			$replacements_description['Exo Suit'] = 'Trench Coat';
			$replacements_description['Mech Suit'] = 'Birthday Suit';

			$replacements['Tactical Katana'] = 'Dildo';
			$replacements['Sniper Rifle'] = 'Butt Plug';
			$replacements['Melee'] = 'Vibrator';
			$replacements['Grenade'] = 'Anal Beads';
			$replacements['Demolition'] = 'Paddle';
			$replacements['Smoke Bomb'] = 'Whip';
			$replacements['Machine Gun'] = 'Money Gun';
			$replacements['Flamethrower'] = 'Candle';
			$replacements['Rocket Launcher'] = 'Double Dildo';
			$replacements['Pull Grenade Pin'] = 'Insert Anal Beads';
			$replacements['Throw Grenade'] = 'Remove Anal Beads';
			$replacements['Load Machine Gun'] = 'Load Money Gun';
			$replacements['Flamethrower Ignition'] = 'Approach Mistress';
			$replacements['Flamethrower Spray'] = 'Address Mistress';
			$replacements['Flamethrower Flame'] = 'Drip Candle Wax';
			$replacements['Flamethrower Fire'] = 'Pour Candle Wax';
			$replacements['Load Rocket Launcher'] = 'Double Dildo';
			$replacements['Rocket Launcher Gunfire'] = 'Double Dildo';
			$replacements['Rocket Launcher Explosion'] = 'Double Dildo Finale';
			
			$replacements_description['Tactical Katana'] = 'Dildo';
			$replacements_description['Sniper Rifle'] = 'Butt Plug';
			$replacements_description['Melee'] = 'Vibrator';
			$replacements_description['Grenade'] = 'Anal Beads';
			$replacements_description['Demolition'] = 'Paddle';
			$replacements_description['Smoke Bomb'] = 'Whip';
			$replacements_description['Machine Gun'] = 'Money Gun';
			$replacements_description['Flamethrower'] = 'Candle';
			$replacements_description['Rocket Launcher'] = 'Double Dildo';

			$replacements['tactical-katana'] = 'dildo';
	    	$replacements['sniper-rifle'] = 'butt-plug';
	    	$replacements['melee'] = 'vibrator';
	    	$replacements['grenade'] = 'anal-beads';
			$replacements['demolition'] = 'paddle';
			$replacements['smoke-bomb'] = 'whip';
			$replacements['machine-gun'] = 'money-gun';
			$replacements['flamethrower'] = 'candle';
			$replacements['rocket-launcher'] = 'double-dildo';

			$replacements['Night Vision Goggles'] = 'Fuzzy Handcuffs';
			$replacements['Radar'] = 'Ball Gag';
			$replacements['Radio'] = 'ATM';
			$replacements['Jet Pack'] = 'Drugs';
			$replacements['Drop Box'] = 'Grab Bag';
			$replacements['Medkit'] = 'Sexy Nurse';
			$replacements['Reinforcements'] = 'Make It Rain';
			$replacements['Pilot'] = 'Money';
			$replacements['Airstrike'] = 'Money';
			$replacements['Jet Pack Takeoff'] = 'Drugs';
			$replacements['Jet Pack Fly'] = 'Trippin';
			
			$replacements_description['Night Vision Goggles'] = 'Fuzzy Handcuffs';
			$replacements_description['Radar'] = 'Ball Gag';
			$replacements_description['Radio'] = 'ATM';
			$replacements_description['Jet Pack'] = 'Drugs';
			$replacements_description['Drop Box'] = 'Grab Bag';
			$replacements_description['Medkit'] = 'Sexy Nurse';

			$replacements['night-vision-goggles'] = 'handcuffs';
			$replacements['radar'] = 'ball-gag';
			$replacements['radio'] = 'atm';
			$replacements['jet-pack'] = 'drugs';
			$replacements['drop-box'] = 'grab-bag';
			$replacements['medkit'] = 'nurse';

			$replacements['base-armor'] = 'boxers';
			$replacements['light-armor'] = 'basketball-shorts';
			$replacements['medium-armor'] = 'sweat-pants';
			$replacements['heavy-armor'] = 'robe';
			$replacements['ballistic-shield'] = 'fur-coat';
			$replacements['quantum-stealth'] = 'pimp-suit';
			$replacements['exo-suit'] = 'trench-coat';
			$replacements['mech-suit'] = 'cake';
		}
	}
	if(isset($replacements[$text])){
		$text = $replacements[$text];
	}else{
		// Handle descriptions separately to improve load times
		foreach($replacements_description AS $original => $replacement){
			if(str_contains($text, $original)){
				$text = str_replace($original, $replacement, $text);
			}
		}
	}
	return $text;
}

// Create battle with wager
function createBattle($conn, $wager) {
	$sql = "INSERT INTO battles (user_id, wager, project_id, active)
	VALUES ('".$_SESSION['userData']['user_id']."', '".$wager."', '".$_SESSION['userData']['project_id']."', '1')";

	if ($conn->query($sql) === TRUE) {
		// Remove wager from balance if db insertion is successful
	  	removeBalance($conn, $wager, $_SESSION['userData']['user_id']);
		announceBattle($wager);
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

// Delete a battle for a creator and retrieve wager is no opponent score has been logged
function deleteBattle($conn, $battle_id) {
	$wager = getWager($conn, $battle_id);
	$sql = "DELETE FROM battles WHERE id = '".$battle_id."' AND project_id = '".$_SESSION['userData']['project_id']."' AND opponent_score = '0'";
	if ($conn->query($sql) === TRUE) {
	  //echo "Record deleted successfully";
		addBalance($conn, $wager, $_SESSION['userData']['user_id']);
	} else {
	    echo "<script type='text/javascript'>alert('An opponent logged a score before you canceled. Please defend in your battle.');</script>";
	}
}

// Get wager amount for a specific battle
function getWager($conn, $battle_id){
	$sql = "SELECT wager FROM battles WHERE id = '".$battle_id."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
			return $row["wager"];
		}
	}
}

// Get opponent score for a specific battle
function getOpponentScore($conn, $battle_id){
	$sql = "SELECT opponent_score FROM battles WHERE id = '".$battle_id."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
			return $row["opponent_score"];
		}
	}
}

// Get creator score for a specific battle
function getCreatorScore($conn, $battle_id){
	$sql = "SELECT user_score FROM battles WHERE id = '".$battle_id."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
			return $row["user_score"];
		}
	}
}

// Get opponent id for a specific battle
function getOpponentID($conn, $battle_id){
	$sql = "SELECT opponent_id FROM battles WHERE id = '".$battle_id."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
			return $row["opponent_id"];
		}
	}
}

// Get opponent username for a specific battle
function getOpponentUsername($conn, $battle_id){
	$sql = "SELECT username FROM battles INNER JOIN users ON battles.opponent_id = users.id WHERE battles.id = '".$battle_id."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
			return $row["username"];
		}
	}
}

// Get creator username for a specific battle
function getCreatorUsername($conn, $battle_id){
	$sql = "SELECT username FROM battles INNER JOIN users ON battles.user_id = users.id WHERE battles.id = '".$battle_id."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
			return $row["username"];
		}
	}
}

// Announce battle
function announceBattle($wager){
	global $prefix;
	$title = "New Battle Created";
	$description = $_SESSION['userData']['name']." created a new battle wagering ".$wager." $".evaluateText("SCRIP");
	$imageurl = "https://www.madballs.net".$prefix."images/dropship.jpg";
	discordmsg($title, $description, $imageurl);
}

// Announce battle results
function announceBattleResults($conn, $type, $user_id, $battle_id){
	global $prefix;
	$wager = getWager($conn, $battle_id);
	$opponent = getOpponentUsername($conn, $battle_id);
	$creator = getCreatorUsername($conn, $battle_id);
	
	$title = "Dead on Round ".$_SESSION['userData']['score']." during PvP ".evaluateText("Battle");
	// Disabling inventory list for battles because it's tied to results and game id
	//ob_start(); // Start output buffering
	//checkPlayerItems($conn);
	//$list = ob_get_contents(); // Store buffer in variable
	//ob_end_clean(); // End buffering and clean up
	
	// Append this to description variable if you get it working
	// "\n".evaluateText($list)
	if($type == "opponent"){
		$description = $_SESSION['userData']['name']." died during Round ".$_SESSION['userData']['score']." in battle with ".$creator;
	}else if($type == "creator"){
		$opponent_score = getOpponentScore($conn, $battle_id);
		$battle_markup = "";
		if($_SESSION['userData']['score'] > $opponent_score){
			$title = "WINNER: ".$title;
			$battle_markup = " and won ".$wager." $".evaluateText("SCRIP")." against score of ".$opponent_score;
		}else if($_SESSION['userData']['score'] < $opponent_score){
			$title = "LOSER: ".$title;
			$battle_markup = " and lost ".$wager." $".evaluateText("SCRIP")." against score of ".$opponent_score;
		}else if($_SESSION['userData']['score'] == $opponent_score){
			$title = "TIE: ".$title;
			$battle_markup = " and kept ".$wager." $".evaluateText("SCRIP")." by tying with score of ".$opponent_score;
		}
		$description = $_SESSION['userData']['name']." died during Round ".$_SESSION['userData']['score'].$battle_markup." by ".$opponent;
	}
	$imageurl = "https://www.madballs.net".$prefix."images/die/".rand(1,3).".gif?var=123";
	discordmsg($title, $description, $imageurl);
}

// Log battle score for opponent or creator. If creator, assign wager to the winner of the battle
function logBattleScore($conn, $type, $user_id, $battle_id){
	$wager = getWager($conn, $battle_id);
	if($type == "opponent"){
		$opponent_id = $user_id;
		$sql = "UPDATE battles SET opponent_score ='".$_SESSION['userData']['score']."', opponent_id = '".$user_id."' WHERE id='".$battle_id."'";
		if ($conn->query($sql) === TRUE) {
		  announceBattleResults($conn, "opponent", $user_id, $battle_id);
   		  removeBalance($conn, $wager, $opponent_id);
		  //echo "New record created successfully";
		  //echo "<script type='text/javascript'>alert('Your battle score of ".$_SESSION['userData']['score']." has been logged.');</script>";
		  unset($_SESSION['userData']['score']);
		  //unset($_SESSION['userData']['battle_id']);
		  unset($_SESSION['userData']['opponent_id']);
   		  removeInventory($conn);
		} else {
		  //echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}else if($type == "creator"){
		$creator_id = $user_id;
		$sql = "UPDATE battles SET user_score ='".$_SESSION['userData']['score']."', active = '0' WHERE id='".$battle_id."'";
		if ($conn->query($sql) === TRUE) {
			announceBattleResults($conn, "creator", $user_id, $battle_id);
			$opponent_score = getOpponentScore($conn, $battle_id);
			$opponent_id = getOpponentID($conn, $battle_id);
			if($_SESSION['userData']['score'] > $opponent_score){
				addBalance($conn, ($wager*2), $_SESSION['userData']['user_id']);
				//echo "<script type='text/javascript'>alert('Your battle score of ".$_SESSION['userData']['score']." has been logged. You beat the opponent score of ".$opponent_score."');</script>";
			}else if($_SESSION['userData']['score'] < $opponent_score){
				addBalance($conn, ($wager*2), $opponent_id);
				//echo "<script type='text/javascript'>alert('Your battle score of ".$_SESSION['userData']['score']." has been logged. You lost to the opponent score of ".$opponent_score."');</script>";
			// In the case of a tie, give wager back to both players
			}else if($_SESSION['userData']['score'] == $opponent_score){
				addBalance($conn, $wager, $_SESSION['userData']['user_id']);
				addBalance($conn, $wager, $opponent_id);
			}
			//echo "New record created successfully";
			unset($_SESSION['userData']['score']);
			//unset($_SESSION['userData']['battle_id']);
			unset($_SESSION['userData']['creator_id']);
 			removeInventory($conn);
		} else {
		  //echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

// Get current battles
function getBattles($conn) {
	$sql = "SELECT battles.id AS battle_id, user_id, wager, opponent_score, username FROM battles INNER JOIN users ON users.id = battles.user_id WHERE battles.project_id = '".$_SESSION['userData']['project_id']."' AND active = '1' ORDER BY battles.date_created DESC";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
	  while($row = $result->fetch_assoc()) {
		echo "<ul class='roles'>";
		echo "<li class='role'><strong>Battle:</strong>&nbsp;".$row["username"]."&nbsp;";
		echo "<strong>Wager:</strong>&nbsp;".$row["wager"]."&nbsp;$".evaluateText("SCRIP")."&nbsp;";
		// Show accept button if player didn't create the battle, they have enough currency and they're not currently battling
		// $row["user_id"] != $_SESSION['userData']['user_id'] && 
		if($row["opponent_score"] == 0 && checkBalance($conn) >= $row["wager"] && !isset($_SESSION['userData']['battle_id'])){
			echo '<form id="opponentForm" action="dashboard.php#barracks" method="post">
			  <input type="hidden" id="opponent_id" name="opponent_id" value="'.$_SESSION['userData']['user_id'].'">
			  <input type="hidden" id="battle_id" name="battle_id" value="'.$row["battle_id"].'">
			  <input class="small-button" type="submit" value="Accept">
			</form>';
		}
		// Check if user created game, if so provide cancellation form if an opponent hasn't logged a score yet.
		if($row["user_id"] == $_SESSION['userData']['user_id'] && $row["opponent_score"] == 0){
			echo '<form id="cancelForm" action="battles.php" method="post">
			  <input type="hidden" id="battle_id" name="battle_id" value="'.$row["battle_id"].'">
			  <input class="small-button" type="submit" value="Cancel">
			</form>';
		}
		if($row["user_id"] == $_SESSION['userData']['user_id'] && $row["opponent_score"] != 0){
			echo '<form id="creatorForm" action="dashboard.php#barracks" method="post">
			  <input type="hidden" id="creator_id" name="creator_id" value="'.$_SESSION['userData']['user_id'].'">
			  <input type="hidden" id="battle_id" name="battle_id" value="'.$row["battle_id"].'">
			  <input class="small-button" type="submit" value="Defend">
			</form>';
		}
		echo "</li>";
		echo "</ul>";
	  }
	}
}

// Get participating NFT projects
function getProjects($conn) {
	$sql = "SELECT id, name FROM projects";
	$result = $conn->query($sql);
	$projects = array();
	if ($result->num_rows > 0) {
	  // output data of each row
	  while($row = $result->fetch_assoc()) {
		$projects[$row["id"]] = $row["name"];
	  }
	  return $projects;
	}
}

// Get project name
function getProjectName($conn) {
	$sql = "SELECT name FROM projects WHERE id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	  // output data of each row
	  while($row = $result->fetch_assoc()) {
		return $row["name"];
	  }
	}
}

// Get policy Id for current project
function getProjectPolicyId($conn) {
	$sql = "SELECT policy_id FROM projects WHERE id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);
	$projects = array();
	if ($result->num_rows > 0) {
	  // output data of each row
	  while($row = $result->fetch_assoc()) {
		return $row["policy_id"];
	  }
	}
}

// Check if user already exists, if not... create them.
function checkUser($conn) {
	if(isset($_SESSION['userData']['discord_id'])){
		$sql = "SELECT id, discord_id, username FROM users WHERE discord_id='".$_SESSION['userData']['discord_id']."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
		    //echo "id: " . $row["id"]. " - Discord ID: " . $row["discord_id"]. " Username: " . $row["username"]. "<br>";
	    	$_SESSION['userData']['user_id'] = strval($row["id"]);
		  }
		} else {
		  //echo "0 results";
		  createUser ($conn, $_SESSION);
		}
	}
}

// Create a user that has visited the site for the first time.
function createUser($conn) {
	$sql = "INSERT INTO users (discord_id, username)
	VALUES ('".$_SESSION['userData']['discord_id']."', '".$_SESSION['userData']['name']."')";

	if ($conn->query($sql) === TRUE) {
	  //echo "New record created successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
	// Immediately check user to set session variable and prevent first run errors
	checkUser($conn);
}

// Update user's Cardano address
function updateAddress($conn, $address) {
	$sql = "UPDATE users SET address='".$address."' WHERE id='".$_SESSION['userData']['user_id']."'";
	if ($conn->query($sql) === TRUE) {
	  //echo "New record created successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

// Check user's Cardano address
function checkAddress($conn) {
	if(isset($_SESSION['userData']['user_id'])){
		$sql = "SELECT address FROM users WHERE id='".$_SESSION['userData']['user_id']."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
		    //echo "id: " . $row["id"]. " - Discord ID: " . $row["discord_id"]. " Username: " . $row["username"]. "<br>";
	    	return $row["address"];
		  }
		} else {
		  //echo "0 results";
		}
	}
}

// Check to see if an active game exists. If not, unset session variable for game id.
function checkGame($conn) {
	if(isset($_SESSION['userData']['project_id'])){
		$sql = "SELECT id, active FROM games WHERE active=1 AND project_id = '".$_SESSION['userData']['project_id']."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
		    //echo "id: " . $row["id"]. " - Active: " . $row["active"]. "<br>";
	    	$_SESSION['userData']['game_id'] = strval($row["id"]);
		  }
		} else {
		  //echo "0 results";
	        unset($_SESSION['userData']['game_id']);
		}
	}
}

// Resurrect user's current squad
function resurrectSquad($conn){
	$sql = "UPDATE soldiers SET deceased = '0', active = '0' WHERE deceased = 1 AND active = 0 AND user_id = '".$_SESSION['userData']['user_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	if ($conn->query($sql) === TRUE) {
	  //echo "Record updated successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

// Kill user's current squad
function killSquad($conn){
	$sql = "UPDATE soldiers SET deceased = '1', active = '0' WHERE active = 1 AND user_id = '".$_SESSION['userData']['user_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	if ($conn->query($sql) === TRUE) {
	  //echo "Record updated successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

// Check squad count
function checkSquadCount($conn){
	$sql = "SELECT COUNT(id) AS total FROM soldiers WHERE user_id='".$_SESSION['userData']['user_id']."' AND active = '1' AND deceased = '0' AND project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			return $row['total'];
		}
	}
}

// Deploy user's soldier to a squad, or retrieve
function deploySoldier($conn, $id, $active){
	// Check to see if squad is full or not
	if((checkSquadCount($conn) < 4 && $active==1) || $active==0){
		// NEED TO CHECK IF PLAYER OWNS SOLDIER BEFORE UPDATING, A HACKER COULD BRUTE FORCE THE SOLDIER ID IN THE FORM AND DEPLOY/WITHDRAW ANOTHER PLAYER'S SOLDIER
		$sql = "UPDATE soldiers SET active = '".$active."' WHERE id = '".$id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
		if ($conn->query($sql) === TRUE) {
		  //echo "Record updated successfully";
		} else {
		  //echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}else{
		echo "<script type='text/javascript'>alert('Your squad is full.');</script>";
	}
}

// Get soldiers for user
function getSoldiers($conn, $active, $filterby="", $all=false){
	if($filterby != "None"){
		if($filterby == "Heavy" || $filterby == "Medium" || $filterby == "Light" || $filterby == "Base"){
			$filterby = "AND armor = '".$filterby."' ";
		}else if($filterby == "Medkit" || $filterby == "Demolition" || $filterby == "Melee"){
			$filterby = "AND gear = '".$filterby."' ";
		}
	}else{
		$filterby = "";
	}
	$active_clause = "";
	if($active != 2){
		$active_clause = "AND active = '".$active."' ";
	}
	$user_clause = "user_id != 0 ";
	if(!$all){
		$user_clause = "user_id = '".$_SESSION['userData']['user_id']."' ";
	}

	$sql = "SELECT name, asset_name, ipfs, rank, armor, gear, level, username, deceased, active, soldiers.id AS soldier_id FROM soldiers INNER JOIN users ON users.id = soldiers.user_id WHERE ".$user_clause.$active_clause.$filterby." AND project_id = '".$_SESSION['userData']['project_id']."' ORDER BY deceased, name";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
	  $troopcounter = 0;
	  while($row = $result->fetch_assoc()) {
		$troopcounter++;
	    echo "<div class='nft'><div class='nft-data ".(($row["deceased"]==1)?"deceased":"")."'>";
	substr("abcdef", -3, 1);
		echo "<span class='nft-name'>".substr($row["name"], 0, 19)."</span>";
		if($_SESSION['userData']['project_id'] == 1){
			echo "<span class='nft-image'><img src='images/nfts/".$row["asset_name"].".jpg'/></span>";
		}else{
			echo "<span class='nft-image'><img src='https://image-optimizer.jpgstoreapis.com/".$row["ipfs"]."'/></span>";
		}
		echo "<span class='nft-rank'><strong>Rank</strong><br>".$row["rank"]."</span>
		<span class='nft-armor'><strong>Armor</strong><br>".evaluateText($row["armor"])."</span>
		<span class='nft-gear'><strong>Gear</strong><br>".evaluateText($row["gear"])."</span>
		<span class='nft-level'><strong>Level</strong><br>".$row["level"]."</span>";
		if($row["deceased"] == "0"){
			if($row["active"] == "0"){
				if(!$all){
					renderDeployButton($row["soldier_id"], 1);
				}else{
					echo "<span class='status'><strong>Status</strong><br>Active</span>";
				}
			}else{
				if(!$all){
					renderDeployButton($row["soldier_id"], 0);
					setSquadAttributes($row);
				}else{
					echo "<span class='status'><strong>Status</strong><br>Deployed</span>";
				}
			}
		}else{
			echo "<span class='status'><strong>Status</strong><br>Deceased</span>";
		}
		if($all){
			echo "<span class='nft-owner'><strong>Owner</strong><br>".$row["username"]."</span>";
		}
		echo "</div></div>";
	  }
	  if($troopcounter < 4 && $active == 1){
		for ($i = 1; $i < 5-$troopcounter; $i++) {
		    echo "<div class='nft empty-slot'><div class='nft-data'>
			<span class='nft-name'>EMPTY SLOT #".$i."</span>
			<span class='nft-image'><img src='images/nfts/placeholder.jpg'></span>
			<span class='nft-rank'><strong>Rank</strong><br>Grunt</span>
			<span class='nft-armor'><strong>Armor</strong><br>None</span>
			<span class='nft-gear'><strong>Gear</strong><br>None</span>
			<span class='nft-level'><strong>Level</strong><br>0</span><br><br><br>
			</div></div>";
		}
	  }
	} else {
	  //echo "0 results";
	}
}

// Initial squad and set global attributes
function setSquad($conn){
	$sql = "SELECT * FROM soldiers WHERE user_id = '".$_SESSION['userData']['user_id']."' AND active = 1 AND project_id = '".$_SESSION['userData']['project_id']."' ORDER BY deceased, name";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	  // output data of each row
	  while($row = $result->fetch_assoc()) {
		if($row["deceased"] == "0"){
			setSquadAttributes($row);
		}
	  }
	} else {
	  //echo "0 results";
	}
}

// Set global NFT attribute variables based on the current status of the user's squad
function setSquadAttributes($row){
	global $heavy, $medium, $light, $base, $extralife, $demolition, $melee, $roleMarkup;
	// Set NFT attribute global variables for current squad
	if($row["armor"] == "Heavy"){
	    $heavy = 'true';
	}
	if($row["armor"] == "Medium"){
		$medium = 'true';
	}
	if($row["armor"] == "Light"){
		$light = 'true';
	}
	if($row["armor"] == "Base"){
		$base = 'true';
	}
	if($row["gear"] == "Medkit"){
		$extralife = 'true';
	}
	if($row["gear"] == "Demolition"){
		$demolition = 'true';
	}
	if($row["gear"] == "Melee"){
		$melee = 'true';
	}
}

// Render deploy button for non-deceased soldiers
function renderDeployButton($id, $deploy=1){
	echo "
	<form action='dashboard.php#barracks' method='post'>
	  <input type='hidden' id='soldier_id' name='soldier_id' value='".$id."'>
	  <input type='hidden' id='deploy' name='deploy' value='".$deploy."'>
	  <input class='small-button' type='submit' value='".(($deploy==1)?"Deploy":"Withdraw")."'>
	</form>";
}

// Check if soldier already exists
function checkSoldier($conn, $asset_name){
	$sql = "SELECT * FROM soldiers WHERE asset_name = '".$asset_name."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		return true;
	}else{
		return false;
	}
}

function createSoldier($conn, $asset_name, $name, $title, $rank, $armor, $gear, $level, $ipfs){
	$sql = "INSERT INTO soldiers (asset_name, name, title, rank, armor, gear, level, ipfs, project_id, user_id)
	VALUES ('".$asset_name."', '".$name."', '".$title."', '".$rank."', '".$armor."', '".$gear."', '".$level."', '".$ipfs."', '".$_SESSION['userData']['project_id']."', '".$_SESSION['userData']['user_id']."')";

	if ($conn->query($sql) === TRUE) {
	  //echo "New record created successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

// Assign soldiers to users based on NFT holdings
function updateSoldiers($conn, $asset_names) {
	// Clear out all NFT associations for user
	$sql = "UPDATE soldiers SET user_id = 0 WHERE user_id = '".$_SESSION['userData']['user_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	if ($conn->query($sql) === TRUE) {
	  //echo "New record created successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
	// Re-establish all NFT associations for user
	$sql = "UPDATE soldiers SET user_id = '".$_SESSION['userData']['user_id']."' WHERE asset_name IN ('".$asset_names."') AND project_id = '".$_SESSION['userData']['project_id']."'";
	if ($conn->query($sql) === TRUE) {
	  //echo "New record created successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

// Reset all soldiers upon game deactivation
function resetSoldiers($conn){
	$sql = "UPDATE soldiers SET active = 0, deceased = 0 WHERE project_id = '".$_SESSION['userData']['project_id']."'";
	if ($conn->query($sql) === TRUE) {
	  //echo "Record updated successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

// Check score for active game id and current user
function checkScore($conn) {
	$sql = "SELECT id, game_id, user_id, score FROM results WHERE user_id='".$_SESSION['userData']['user_id']."' AND game_id='".$_SESSION['userData']['game_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
	  while($row = $result->fetch_assoc()) {
	    //echo "id: " . $row["id"]. " - Score: " . $row["score"]. "<br>";
    	$_SESSION['userData']['current_score'] = strval($row["score"]);
	  }
	} else {
	  //echo "0 results";
		unset($_SESSION['userData']['current_score']);
	}
}

// Delete last result and any associations with items
function deleteResult($conn){
	$result_id = checkResultID($conn);
	if(isset($result_id)){
		$sql = "DELETE FROM results WHERE id = '".$result_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
		if ($conn->query($sql) === TRUE) {
		  //echo "Record deleted successfully";
		} else {
		  //echo "Error: " . $sql . "<br>" . $conn->error;
		}
		$sql = "DELETE FROM results_items WHERE result_id = '".$result_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
		if ($conn->query($sql) === TRUE) {
		  //echo "Record deleted successfully";
		} else {
		  //echo "Error: " . $sql . "<br>" . $conn->error;
		}
		$sql = "DELETE FROM results_soldiers WHERE result_id = '".$result_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
		if ($conn->query($sql) === TRUE) {
		  //echo "Record deleted successfully";
		} else {
		  //echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

// Log the user's score for the active game id
function logScore($conn, $replay) {
	// Check for the off chance that someone ran an inactive game right after a game was deactivated
	checkGame($conn);
	if(isset($_SESSION['userData']['game_id'])) {
		// Delete previous result for current game, if it exists
		deleteResult($conn);
		$sql = "INSERT INTO results (game_id, user_id, score, replay, project_id)
		VALUES ('".$_SESSION['userData']['game_id']."', '".$_SESSION['userData']['user_id']."', '".$_SESSION['userData']['score']."', '".htmlspecialchars($replay)."', '".$_SESSION['userData']['project_id']."')";

		if ($conn->query($sql) === TRUE) {
		  //echo "New record created successfully";
			checkActiveInventory($conn);
			removeInventory($conn);
			checkActiveSoldiers($conn);
			killSquad($conn);
		} else {
		  //echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}else{
		echo "<script type='text/javascript'>alert('The game was already deactivated when you did this run. Your score has not been saved. Any item purchases you made will remain in your inventory for the next game.');</script>";
	}
}

// Check active inventory
function checkActiveInventory($conn) {
	$sql = "SELECT item_id FROM inventory WHERE user_id='".$_SESSION['userData']['user_id']."' AND active='1' AND project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	  // output data of each row
	  while($row = $result->fetch_assoc()) {
		 logResultsItems($conn, $row["item_id"]);
	  }
	} else {
	  //echo "0 results";
	}
}

// Associates used items with results
function logResultsItems($conn, $item_id) {
	$result_id = checkResultID($conn);
	$sql = "INSERT INTO results_items (result_id, item_id, project_id)
	VALUES ('".$result_id."', '".$item_id."', '".$_SESSION['userData']['project_id']."')";

	if ($conn->query($sql) === TRUE) {
	  //echo "New record created successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

// Check active soldiers
function checkActiveSoldiers($conn) {
	$sql = "SELECT id FROM soldiers WHERE user_id='".$_SESSION['userData']['user_id']."' AND active='1' AND project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	  // output data of each row
	  while($row = $result->fetch_assoc()) {
		 logResultsSoldiers($conn, $row["id"]);
	  }
	} else {
	  //echo "0 results";
	}
}

// Associates active soldiers with results
function logResultsSoldiers($conn, $soldier_id) {
	$result_id = checkResultID($conn);
	$sql = "INSERT INTO results_soldiers (result_id, soldier_id, project_id)
	VALUES ('".$result_id."', '".$soldier_id."', '".$_SESSION['userData']['project_id']."')";

	if ($conn->query($sql) === TRUE) {
	  //echo "New record created successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

// Get result ID
function checkResultID($conn) {
	$sql = "SELECT id FROM results WHERE user_id='".$_SESSION['userData']['user_id']."' AND game_id='".$_SESSION['userData']['game_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	  // output data of each row
	  while($row = $result->fetch_assoc()) {
		return $row["id"];
	  }
	} else {
	  //echo "0 results";
	}
}

// Deactivate inventory from user after playing game
function removeInventory($conn) {
	$sql = "UPDATE inventory SET active='0' WHERE user_id='".$_SESSION['userData']['user_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	if ($conn->query($sql) === TRUE) {
	  //echo "Record updated successfully";
	} else {
	  //echo "Error updating record: " . $conn->error;
	}
}

// Get drop ship markup results for player
function getReplay($conn, $result_id) {
	$sql = "SELECT replay FROM results WHERE id = '".$result_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			return htmlspecialchars_decode($row["replay"]);
		}
	}
}

// Get items for drop box
function getDropBoxItems($conn){
	$sql = "SELECT name FROM items WHERE type ='Weapon'";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
		$items = array();
		$item_index = 1;
		while($row = $result->fetch_assoc()) {
			$items[$item_index] = strtolower(str_replace(" ", "_", $row["name"]));
			$item_index++;
		}
	}
	return $items;
}

// Get specific player's average score across all games
function getAverageScore($conn){
	if(isset($_SESSION['userData']['user_id'])){
		$sql = "SELECT AVG(score) AS score_avg FROM results WHERE results.user_id='".$_SESSION['userData']['user_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
		$result = $conn->query($sql);
	
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				return (!(empty($row["score_avg"]))?round($row["score_avg"]):"NA");
			}
		}
	}
}

// Get specific player's top score across all games
function getTopScore($conn){
	if(isset($_SESSION['userData']['user_id'])){
		$sql = "SELECT MAX(score) AS max_score FROM results WHERE results.user_id='".$_SESSION['userData']['user_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
		$result = $conn->query($sql);
	
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				return (!(empty($row["max_score"]))?round($row["max_score"]):"NA");
			}
		}
	}
}

// Number of scores logged aka games played
function getScoreCount($conn){
	if(isset($_SESSION['userData']['user_id'])){
		$sql = "SELECT COUNT(score) AS total_scores FROM results WHERE results.user_id='".$_SESSION['userData']['user_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
		$result = $conn->query($sql);
	
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				return (!(empty($row["total_scores"]))?round($row["total_scores"]):"NA");
			}
		}
	}
}

// Load marketplace items
function loadAchievements($conn, $heavy_armor, $medium_armor, $light_armor, $base_armor, $melee, $demolition, $medkit) {
	$status = "Locked";
	$all = true;
	
	// Super Soldier achievement
	/* Removing due to Super Soldier constantly being in flux
	if($heavy_armor == "true" && $medium_armor == "true" && $light_armor == "true" && $base_armor == "true" && $melee == "true" && $demolition == "true" && $medkit == "true"){
		$status = "Unlocked";
	}else{
		$status = "Locked";
	}
	loadAchievement($status, "supersoldier", "Super Soldier");*/
	
	//		if(!str_contains($row["name"], "Armor") && $row["name"] != "Melee" && $row["name"] != "Demolition" && $row["name"] != "Medkit"){
	
	// Item achievements
	$sql = "SELECT id, name FROM items ORDER BY type ASC";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	  // output data of each row
	  while($row = $result->fetch_assoc()) {
			$sql = "SELECT item_id FROM inventory WHERE user_id = '".$_SESSION['userData']['user_id']."' AND item_id = '".$row["id"]."' AND project_id = '".$_SESSION['userData']['project_id']."'";
			$inventory_result = $conn->query($sql);
			if ($inventory_result->num_rows > 0) {
				$status = "Unlocked";
			} else {
				// Check if NFT attributes are present
				/* Commenting this out because NFT attributes are always in flux now
				if($row["name"] == "Heavy Armor" && $heavy_armor == "true"){
					$status = "Unlocked";
				}else if($row["name"] == "Heavy Armor" && $medium_armor == "true"){
					$status = "Unlocked";
				}else if($row["name"] == "Medium Armor" && $light_armor == "true"){
					$status = "Unlocked";
				}else if($row["name"] == "Light Armor" && $base_armor == "true"){
					$status = "Unlocked";
				}else if($row["name"] == "Base Armor" && $melee == "true"){
					$status = "Unlocked";
				}else if($row["name"] == "Melee" && $demolition == "true"){
					$status = "Unlocked";
				}else if($row["name"] == "Demolition" && $medkit == "true"){
					$status = "Unlocked";
				}else if($row["name"] == "Medkit" && $medkit == "true"){
					$status = "Unlocked";
				}else{ 
					$status = "Locked";
					$all = false;
				}*/
			}
			// Ignoring base NFT attribute achievements because they are always in flux now
			if($row["name"] != "Heavy Armor" && $row["name"] != "Medium Armor" && $row["name"] != "Light Armor" && $row["name"] != "Base Armor" && $row["name"] != "Melee" && $row["name"] != "Demolition" && $row["name"] != "Medkit"){
				loadAchievement($status, evaluateText(strtolower(str_replace(" ", "-", $row["name"]))), evaluateText($row["name"]));
			}
	  }
	} else {
	  //echo "0 results";
	}
	
	// Scene achievements
	$sql = "SELECT MAX(score) AS max_score FROM results WHERE user_id = '".$_SESSION['userData']['user_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	for ($index = 7; $index <= 17; $index+=5) {
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
				if ($row['max_score'] >= $index){
					$status = "Unlocked";
				}else{
					$status = "Locked";
					$all = false;
				}
		  }
		} else {
		  //echo "0 results";
		  	$status = "Locked";
			$all = false;
		}
		if($index == 7){
			loadAchievement($status, "beach", evaluateText("Stormed the Beach"));
		}else if($index == 12){
			loadAchievement($status, "hill", evaluateText("Climbed the Hill"));
		}else if($index == 17){
			loadAchievement($status, "bunker", evaluateText("Entered the Bunker"));
		}
	}
	
	// Score achievements
	$sql = "SELECT MAX(score) AS max_score FROM results WHERE user_id = '".$_SESSION['userData']['user_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	for ($index = 2; $index <= 5; $index++) {
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
				if ($row['max_score'] >= $index*10){
					$status = "Unlocked";
				}else{
					$status = "Locked";
					$all = false;
				}
		  }
		} else {
		  //echo "0 results";
		  	$status = "Locked";
			$all = false;
		}
		loadAchievement($status, ($index*10), "Survived ".($index*10)." Rounds");
	}
	
	// SCRIP achievements
	$sql = "SELECT SUM(amount) AS total_amount FROM transactions WHERE user_id = '".$_SESSION['userData']['user_id']."' AND type='credit' AND project_id = '".$_SESSION['userData']['project_id']."'";
	for ($index = 250; $index <= 1000; $index+=250) {
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
				if ($row['total_amount'] >= $index){
					$status = "Unlocked";
				}else{
					$status = "Locked";
					$all = false;
				}
				$total_amount = $row['total_amount'];
		  }
		} else {
		  //echo "0 results";
			$status = "Locked";
			$all = false;
		}
		loadAchievement($status, "scrip", number_format($index)." ".evaluateText("SCRIP")." Earned");
	}
	
	// Game winning achievements
	$sql = "SELECT COUNT(amount) AS amount_count FROM transactions WHERE user_id = '".$_SESSION['userData']['user_id']."' AND type='credit' AND amount='0' AND project_id = '".$_SESSION['userData']['project_id']."'";
	for ($index = 1; $index <= 2; $index++) {
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				if ($row['amount_count'] >= $index){
					// output data of each row
					$status = "Unlocked";	
				} else {
					$status = "Locked";
					$all = false;
				}
			}
		} else {
		  	$status = "Locked";
			$all = false;
		}
		loadAchievement($status, "trophy", "Won ".$index." NFT".(($index > 1)?"s":""));
	}
	
	// Overall achievement
	if($all){
		$status = "Unlocked";

	}else{
		$status = "Locked";
	}
	loadAchievement($status, "trophy", "All Achievements");
}

function loadAchievement($status, $icon, $title){
	echo "<div class='achievement ".strtolower($status)."'><div class='text'>";
    echo "<img class='icon' src='icons/".$icon.".png'/>";
	echo "<span class='title'>".$title."</span>&nbsp;<span class='status'><img class='icon' src='icons/".strtolower($status).".png'/></span></div>";
	echo "</div>";
}

// Load marketplace items
function loadItems($conn, $type, $heavy_armor, $medium_armor, $light_armor, $base_armor, $melee, $demolition, $medkit, $scrip) {
	$sql = "SELECT id, name, description, type, cost, level FROM items WHERE type='".$type."' ORDER BY cost ASC";
	$result = $conn->query($sql);
	
	$scrip = str_replace(',', '', $scrip);

	if ($result->num_rows > 0) {
	  // output data of each row
	  echo "<ul>";
	  while($row = $result->fetch_assoc()) {
	    echo "<li class='item'>
	    <img class='icon' src='icons/".evaluateText(strtolower(str_replace(" ", "-", $row["name"]))).".png'/>
		<table class='inventory-item' width='100%'>
			<tr>
				<td width='50%' vertical-align='center' class='td1 align='left''>
				<strong><u>".evaluateText($row["name"])."</u></strong>
				</td>
				<td width='30%' vertical-align='center' class='td2' align='right'>"
				.$row["cost"]."&nbsp;\$".evaluateText("SCRIP")."</td>
				<td width='20%' vertical-align='center' class='td3' align='center' rowspan='1'>";
				if(isset($_SESSION['userData']['level'])){
					if($_SESSION['userData']['level'] >= $row["level"]) {
						// Check if player has existing role that overrides item purchase or not
						if(!isset(${strtolower(str_replace(" ", "_", $row["name"]))})){
							if($scrip >= $row['cost']){
								renderBuyButton($row['id']);
							}else{
								if(checkSquadCount($conn) > 0){
									echo "<img class='icon pinch' src='icons/pinch.png'>";
								}
							}
						}else{
							if(${strtolower(str_replace(" ", "_", $row["name"]))} == "false"){
								if($scrip >= $row['cost']){
									renderBuyButton($row['id']);
								}else{
									if(checkSquadCount($conn) > 0){
										echo "<img class='icon pinch' src='icons/pinch.png'>";
									}
								}
							}else{
								echo "[&nbsp;&#x2713;&nbsp;]";
							}
						}
					} else {
						echo "Lv. ".$row["level"]."&nbsp;";
					}
				}
				echo "</td>
			</tr>";
			if(isset($_SESSION['userData']['user_id'])){
				$sql = "SELECT AVG(results.score) AS score_avg FROM items INNER JOIN results_items ON results_items.item_id = items.id INNER JOIN results ON results.id = results_items.result_id WHERE type='".$type."' AND items.id='".$row['id']."' AND results.user_id='".$_SESSION['userData']['user_id']."' AND results.project_id = '".$_SESSION['userData']['project_id']."'";
				$avg_result = $conn->query($sql);
			
				if ($avg_result->num_rows > 0) {
					while($avg_row = $avg_result->fetch_assoc()) {
						echo "<tr class='item-stats'><td><strong>Avg Score:</strong> ".(!(empty($avg_row["score_avg"]))?round($avg_row["score_avg"]):"NA")."</td></tr>";
					}
				}
				$sql = "SELECT COUNT(items.id) AS item_total FROM items INNER JOIN results_items ON results_items.item_id = items.id INNER JOIN results ON results.id = results_items.result_id WHERE type='".$type."' AND items.id='".$row['id']."' AND results.user_id='".$_SESSION['userData']['user_id']."' AND results.project_id = '".$_SESSION['userData']['project_id']."'";
				$total_result = $conn->query($sql);
			
				if ($total_result->num_rows > 0) {
					while($total_row = $total_result->fetch_assoc()) {
						echo "<tr class='item-stats'><td><strong>Purchases:</strong> ".$total_row["item_total"]."</i></td></tr>";
					}
				}
			}
			echo "<tr class='item-description'><td colspan='3'><i>".evaluateText($row["description"])."</i></td></tr>";
		echo"</table>
		</li>";
	  }
	  echo "</ul>";
	} else {
	  //echo "0 results";
	}
}

function renderBuyButton($id){
	global $conn;
	// Disable purchasing of items if a squad is not enabled
	if(checkSquadCount($conn) > 0){
		echo "
		<form action='dashboard.php#armory' method='post'>
		  <input type='hidden' id='item_id' name='item_id' value='".$id."'>
		  <input class='small-button' type='submit' value='Buy'>
		</form>";
	}
}

// Buy item
function buyItem ($conn, $item_id, $drop_box){
	// Store
	$balance = 0;
	$balance = checkBalance($conn);
	$itemCost = checkItemCost($conn, $item_id);
	if($drop_box == true){
		$itemCost = 0;
	}
	
	if($balance >= $itemCost){
		// Check if active item of type being purchased already exists.
		$activeType = checkInventoryItemType($conn, checkItemType ($conn, $item_id));
		// Override single item type check for drop box purchase
		if($drop_box == true){
			$activeType = "false";
		}
		
		if($activeType == "false"){
			// Check if item already exists in inventory table for user
			$sql = "SELECT user_id, item_id, active FROM inventory WHERE user_id='".$_SESSION['userData']['user_id']."' AND item_id='".$item_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
			$result = $conn->query($sql);
			
			if($itemCost > 0){
				removeBalance($conn, $itemCost, $_SESSION['userData']['user_id']);
			}
			logDebit($conn, $_SESSION['userData']['user_id'], $item_id, $itemCost);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					// Update existing item
					// Check if item is active or not
					if($row["active"] == "0") {
						// If item does exist and is NOT active, activate item in inventory
						$sql = "UPDATE inventory SET active='1' WHERE user_id='".$_SESSION['userData']['user_id']."' AND item_id='".$item_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
						if ($conn->query($sql) === TRUE) {
						  //echo "Record updated successfully";
						} else {
						  //echo "Error updating record: " . $conn->error;
						}
					}else{
						echo "<script type='text/javascript'>alert('You already own this item.');</script>";
					}
				}
			} else {
			  //echo "0 results";
			    // If item doesn't exist in user inventory, add it for the first time
				$sql = "INSERT INTO inventory (user_id, item_id, project_id, active)
				VALUES ('".$_SESSION['userData']['user_id']."', '".$item_id."', '".$_SESSION['userData']['project_id']."', '1')";

				if ($conn->query($sql) === TRUE) {
				  //echo "New record created successfully";
				} else {
				  //echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
		}else{
			echo "<script type='text/javascript'>alert('You already own an item of this type. You can only hold one item of a specific type in your inventory at a time. You cannot stack items of the same type.');</script>";
		}
	}else{
		echo "<script type='text/javascript'>alert('You do not have enough \$".evaluateText("SCRIP")." to purchase this item.');</script>";
	}
}

// Check cost of a specific item
function checkItemCost ($conn, $item_id) {
	// Check cost of item
	$sql = "SELECT id, cost FROM items WHERE id='".$item_id."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			return $row["cost"];
		}
	} else {
	  //echo "0 results";
	}
}

// Check id of a specific item
function checkItemID ($conn, $item_name) {
	// Check cost of item
	$sql = "SELECT id, name FROM items WHERE name ='".$item_name."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			return $row["id"];
		}
	} else {
	  //echo "0 results";
	}
}

// Check name of a specific item
function checkItemName ($conn, $item_id) {
	// Check cost of item
	$sql = "SELECT id, name FROM items WHERE id='".$item_id."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			return evaluateText($row["name"]);
		}
	} else {
	  //echo "0 results";
	}
}

// Check type of a specific item
function checkItemType ($conn, $item_id) {
	// Check cost of item
	$sql = "SELECT id, type FROM items WHERE id='".$item_id."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			return $row["type"];
		}
	} else {
	  //echo "0 results";
	}
}

// Check if item type is already active in user inventory
function checkInventoryItemType($conn, $item_type) {
	// Check if item type is already active in user inventory
	$sql = "SELECT inventory.item_id, inventory.active, items.type FROM inventory INNER JOIN items ON inventory.item_id = items.id WHERE user_id='".$_SESSION['userData']['user_id']."' AND items.type='".$item_type."' AND inventory.active='1' AND inventory.project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			// Update existing item
			// Check if item is active or not
			if($row["active"] == "1") {
				// If item type already exists and is active, throw error message
				return "true";
			}
		}
	} else {
	  //echo "0 results";
	    // If item type already exist but is NOT active, activate item in inventory
	    return "false";
	}
}

// Remove amount from a specific user's balance
function removeBalance($conn, $itemCost, $user_id) {
	$sql = "SELECT user_id, balance FROM balances WHERE user_id='".$user_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			// Add current score to existing balance
			$balance = $row["balance"] - $itemCost;
			// If user does exist, update record for calculated balance
			$sql = "UPDATE balances SET balance='".$balance."' WHERE user_id='".$user_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
			if ($conn->query($sql) === TRUE) {
			  //echo "Record updated successfully";
			} else {
			  //echo "Error updating record: " . $conn->error;
			}
		}
	}
}

// Add amount for a specific user's balance
function addBalance($conn, $amount, $user_id) {
	$sql = "SELECT user_id, balance FROM balances WHERE user_id='".$user_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			// Add current score to existing balance
			$balance = $row["balance"] + $amount;
			// If user does exist, update record for calculated balance
			$sql = "UPDATE balances SET balance='".$balance."' WHERE user_id='".$user_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
			if ($conn->query($sql) === TRUE) {
			  //echo "Record updated successfully";
			} else {
			  //echo "Error updating record: " . $conn->error;
			}
		}
	}
}

// Reset inventory session to account for all marketplace items before assigning inventory
function resetInventory($conn) {
	$sql = "SELECT name FROM items";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	  	while($row = $result->fetch_assoc()) {
			$_SESSION['userData'][strtolower(str_replace(" ", "_", $row["name"]))] = "false";
		}
	}
}

// Check active inventory for specific user
function checkInventory($conn) {
	if(isset($_SESSION['userData']['user_id'])){
		$sql = "SELECT inventory.user_id, inventory.item_id, inventory.active, items.name, items.type FROM inventory INNER JOIN items ON inventory.item_id = items.id WHERE user_id='".$_SESSION['userData']['user_id']."' AND inventory.project_id = '".$_SESSION['userData']['project_id']."' ORDER BY items.type DESC";
		$result = $conn->query($sql);
		
		resetInventory($conn);
		$item_count = 0;
		if ($result->num_rows > 0) {
		  // output data of each row
			// Clean output for discord leaderboard
		  	echo "<ul>";
		  	while($row = $result->fetch_assoc()) {
				if($row["active"] == "1") {
		    		echo "<li class='role'><img class='icon' src='icons/".evaluateText(strtolower(str_replace(" ", "-", $row["name"]))).".png'/><strong>".evaluateText($row["name"])."</strong></li>";
					// Add active item to session
					$_SESSION['userData'][strtolower(str_replace(" ", "_", $row["name"]))] = "true";
					$item_count++;
				} else {
					// Remove inactive item from session
					$_SESSION['userData'][strtolower(str_replace(" ", "_", $row["name"]))] = "false";
				}
		  	}
			if($item_count == 0){
				echo "<li class='role'><img class='icon' src='icons/cart.png'/>No Items Purchased</li>";
			}
			echo "</ul>";
		} else {
		  //echo "0 results";
		}
	}
}

// Check items stored with results and format with next lines
function checkResultsItemsNextLine($conn, $result_id, $clean) {
	$sql = "SELECT items.name FROM results_items INNER JOIN items ON results_items.item_id=items.id WHERE results_items.result_id='".$result_id."' AND results_items.project_id = '".$_SESSION['userData']['project_id']."' ORDER BY results_items.id DESC";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
		// Clean output for discord leaderboard
	  	while($row = $result->fetch_assoc()) {
			if($clean == "true"){
				echo "\n".$row["name"];
			}else{
				echo "&nbsp;<img class='icon' src='icons/".strtolower(str_replace(" ", "-", $row["name"])).".png'/>";
			}	    	
	  	}
	} else {
	  //echo "0 results";
	}
}

// Check used items for specific player
function checkPlayerItems($conn) {
	$sql = "SELECT results.id, results.game_id, results.user_id, results.score, users.username FROM results INNER JOIN users ON results.user_id=users.id WHERE users.id ='".$_SESSION['userData']['user_id']."' AND game_id='".$_SESSION['userData']['game_id']."' AND results.project_id = '".$_SESSION['userData']['project_id']."' ORDER BY results.score DESC";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
		// Clean output for discord leaderboard
		while($row = $result->fetch_assoc()) {
			//$leaderboardCounter++;
			checkResultsItemsNextLine($conn, $row["id"], "true");
		}
	} else {
	  //echo "0 results";
	}
}

// Check items stored with results
function checkResultsItems($conn, $result_id, $clean) {
	$sql = "SELECT items.name FROM results_items INNER JOIN items ON results_items.item_id=items.id WHERE results_items.result_id='".$result_id."' AND results_items.project_id = '".$_SESSION['userData']['project_id']."' ORDER BY results_items.id DESC";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
		// Clean output for discord leaderboard
		$item_names = array();
	  	while($row = $result->fetch_assoc()) {
			if($clean == "true"){
				$item_names[] = $row["name"];
			}else{
				echo "&nbsp;<img class='icon' src='icons/".evaluateText(strtolower(str_replace(" ", "-", $row["name"]))).".png'/>";
			}	    	
	  	}
		if($clean == "true" && count($item_names) > 0){
			echo " ".implode(", ", $item_names);
		}
	} else {
	  //echo "0 results";
	}
}

// Generate instant replay button for a specific user
function instantReplayButton($result_id, $small_button){
	$button = "";
	$value = "";
	if($small_button == true){
		$button = "small-button";
		$value = "&#9658;";
	}else{
		$button = "button";
		$value = "Instant Replay";
	}
	echo '<form action="dashboard.php" method="post">
  	  <input type="hidden" id="result_id" name="result_id" value="'.$result_id.'">
	  <input type="hidden" id="instant_replay" name="instant_replay" value="true">
	  <input class="'.$button.'" type="submit" value="'.$value.'">
	</form>';
}

function getResultsSoldiers($conn, $result_id){
	$sql = "SELECT name, asset_name, ipfs FROM soldiers INNER JOIN results_soldiers ON soldiers.id=results_soldiers.soldier_id WHERE results_soldiers.result_id='".$result_id."' AND soldiers.project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$counter = 0;
		echo "<div class='leaderboard-nfts'>";
		while($row = $result->fetch_assoc()) {
			$counter++;
			if($_SESSION['userData']['project_id'] == 1){
				echo "<span class='leaderboard-nft'><img src='images/nfts/".$row["asset_name"].".jpg'/></span>";
			}else{
				echo "<span class='leaderboard-nft'><img src='https://image-optimizer.jpgstoreapis.com/".$row["ipfs"]."'/></span>";
			}
		}
		for($i=1; $i<=(4-$counter); $i++){
			echo "<span class='leaderboard-nft'><img src='images/nfts/placeholder.jpg'></span>";
		}
		echo "</div>";
	} else {
	  //echo "0 results";
	}
}

// Check leaderboard for discord and site display
function checkLeaderboard($conn, $clean) {
	global $discordid_kryptman, $discordid_oculusorbus, $discordid_ohhmeed;
	$sql = "SELECT results.id, results.game_id, results.user_id, results.score, users.username, users.id AS user_id FROM results INNER JOIN users ON results.user_id=users.id WHERE game_id='".$_SESSION['userData']['game_id']."' AND results.project_id = '".$_SESSION['userData']['project_id']."' ORDER BY results.score DESC";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
		// Clean output for discord leaderboard
		if($clean == "true") {
			$leaderboardCounter = 0;
			while($row = $result->fetch_assoc()) {
				$leaderboardCounter++;
				echo $leaderboardCounter.". ".$row["username"]." (".$row["score"].")";
				checkResultsItems($conn, $row["id"], "true");
				echo "\n";
			}
		// Formatted output for website leaderboard
		} else {
			$leaderboardCounter = 0;
		  	echo "<ul id='leaderboard'>";
		  	while($row = $result->fetch_assoc()) {
				$leaderboardCounter++;
		    	echo "<li class='role'><table width='100%'><tr><td width='90%'>".$leaderboardCounter.". <strong>".$row["username"]. "</strong> (" . $row["score"]. ")";
				checkResultsItems($conn, $row["id"], "false");
				$data = checkXP($conn, $row["user_id"]);
				$level = $data['level'];
				// Only show instant replays to players at the same level or below
				echo "</td><td width='10%' align='right'>";
				if($_SESSION['userData']['level'] >= $level || $_SESSION['userData']['discord_id'] == $discordid_kryptman || $_SESSION['userData']['discord_id'] == $discordid_oculusorbus || $_SESSION['userData']['discord_id'] == $discordid_ohhmeed){
					instantReplayButton($row["id"], true);
				}
				echo "</td></tr></table></li>";
				getResultsSoldiers($conn, $row["id"]);
		  	}
			echo "</ul>";
		}
	} else {
	  //echo "0 results";
	}
}

// Check ATH leaderboard for discord
function checkATHLeaderboard($conn, $clean) {
	global $discordid_kryptman, $discordid_oculusorbus, $discordid_ohhmeed;
	$sql = "SELECT results.id, results.user_id, results.game_id, users.username, MAX(results.score) as max_score, replay FROM results LEFT JOIN users ON results.user_id = users.id WHERE results.project_id = '".$_SESSION['userData']['project_id']."' GROUP BY results.user_id ORDER BY max_score DESC";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
		// Clean output for discord leaderboard
		$leaderboardCounter = 0;
		$result_id = 0;
		if($clean == "true") {
			while($row = $result->fetch_assoc()) {
				$leaderboardCounter++;
				echo $leaderboardCounter.". ".$row["username"]." (".$row["max_score"].")";
				$result_id = checkMaxScoreResultID($conn, $row["user_id"], $row["max_score"]);
				checkResultsItems($conn, $result_id, "true");
				echo "\n";
			}
		} else {
			$leaderboardCounter = 0;
		  	echo "<ul id='leaderboard'>";
		  	while($row = $result->fetch_assoc()) {
				$leaderboardCounter++;
		    	echo "<li class='role'><table width='100%'><tr><td width='90%'>".$leaderboardCounter.". <strong>".$row["username"]."</strong> (" . $row["max_score"]. ")";
				$result_id = checkMaxScoreResultID($conn, $row["user_id"], $row["max_score"]);
				checkResultsItems($conn, $result_id, "false");
				$data = checkXP($conn, $row["user_id"]);
				$level = $data['level'];
				// Only show instant replays to players at the same level or below
				echo "</td><td width='10%' align='right'>";
				if($_SESSION['userData']['level'] >= $level || $_SESSION['userData']['discord_id'] == $discordid_kryptman || $_SESSION['userData']['discord_id'] == $discordid_oculusorbus || $_SESSION['userData']['discord_id'] == $discordid_ohhmeed){
					instantReplayButton($result_id, true);
				}
				echo "</td></tr></table></li>";
		  	}
			echo "</ul>";
		}
	} else {
	  //echo "0 results";
	}
}

// Get Max score result ID
function checkMaxScoreResultID($conn, $user_id, $max_score){
	if(isset($user_id)){
		$sql = "SELECT DISTINCT id, user_id, score FROM results WHERE score = '".$max_score."' AND user_id = '".$user_id."' AND project_id = '".$_SESSION['userData']['project_id']."' ORDER BY id DESC";
		$result = $conn->query($sql);
		while($row = $result->fetch_assoc()) {
			return $row["id"];
		}
	}
}

// Check player XP
function checkXP($conn, $user_id) {
	$data = array();
	$temp = 0;
	$data['xp'] = 0;
	$data['level'] = 0;
	$data['ceiling'] = 100;
	$data['percentage'] = 0;
	$sql = "SELECT SUM(results.score) as xp FROM results INNER JOIN users ON results.user_id=users.id WHERE users.id='".$user_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
		while($row = $result->fetch_assoc()) {
			$data['xp'] = $row["xp"];
		}
		if($data['xp'] != 0){
			$temp = $data['xp']/100;
			$data['ceiling'] = ceil($temp)*100;
			$data['percentage'] = (1-(ceil($temp) - $temp))*100;
			$data['level'] = floor($temp);
		}else{
			$data['xp'] = 0;
			$data['level'] = 0;
			$data['ceiling'] = 100;
			$data['percentage'] = 0;
		}
	} else {
	  //echo "0 results";
	}
	return $data;
}

// Check XP leaderboard for discord and site display
function checkXPLeaderboard($conn, $clean) {
	$sql = "SELECT results.game_id, results.user_id, SUM(results.score) as xp, users.username FROM results INNER JOIN users ON results.user_id=users.id WHERE results.project_id = '".$_SESSION['userData']['project_id']."' GROUP BY results.user_id ORDER BY xp DESC";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
		// Clean output for discord leaderboard
		if($clean == "true") {
			$leaderboardCounter = 0;
			while($row = $result->fetch_assoc()) {
				$leaderboardCounter++;
				$level = floor($row["xp"]/100);
				echo $leaderboardCounter.". ".$row["username"].": Lv. ".$level." - (".$row["xp"]." XP)\n";
			}
		// Formatted output for website leaderboard
		} else {
			$leaderboardCounter = 0;
		  	echo "<ul id='leaderboard'>";
		  	while($row = $result->fetch_assoc()) {
				$leaderboardCounter++;
				$level = floor($row["xp"]/100);
		    	echo "<li>".$leaderboardCounter.". <strong>".$row["username"]. "</strong>: <i>Lv. ".$level."</i> - (" . $row["xp"]. " XP)</li>";
		  	}
			echo "</ul>";
		}
	} else {
	  //echo "0 results";
	}
}

// Admin function to deactivate current game
function deactivateGame($conn) {
	// Log balances before deactivating game
	logBalances($conn);
	// Reset status of all soldiers
	resetSoldiers($conn);
	$sql = "UPDATE games SET active='0' WHERE id='".$_SESSION['userData']['game_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";

	if ($conn->query($sql) === TRUE) {
	  //echo "Record updated successfully";
      unset($_SESSION['userData']['game_id']);
	  unset($_SESSION['userData']['current_score']);
	  unset($_SESSION['userData']['score']);
	} else {
	  //echo "Error updating record: " . $conn->error;
	}
}

// Log balances for players for current game that is being deactivated
function logBalances($conn) {
	$sql = "SELECT results.id, results.game_id, results.user_id, results.score, users.username, games.prizes FROM results INNER JOIN users ON results.user_id=users.id INNER JOIN games ON results.game_id = games.id WHERE game_id='".$_SESSION['userData']['game_id']."' AND results.project_id = '".$_SESSION['userData']['project_id']."' ORDER BY results.score DESC";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
		$counter = 0;
		while($row = $result->fetch_assoc()) {
			$counter++;
			// Check to see if score position is greater than counter. This ensures prize winners don't get $SCRIP rewards.
			if($counter > $row["prizes"]){
				logBalance($conn, $row["user_id"], $row["score"]);
				logCredit($conn, $row["user_id"], $row["id"], $row["score"]);
			}else{
				logCredit($conn, $row["user_id"], $row["id"], 0);
			}
		}
	} else {
	  //echo "0 results";
	}
}

// Log a specific user balance for their score during the active game
function logBalance($conn, $user_id, $score) {
	// Check if user already exists in balance table
	$sql = "SELECT user_id, balance FROM balances WHERE user_id='".$user_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			// Add current score to existing balance
			$score = $score + $row["balance"];
			// If user does exist, update record for calculated balance
			$sql = "UPDATE balances SET balance='".$score."' WHERE user_id='".$user_id."' AND project_id = '".$_SESSION['userData']['project_id']."'";
			if ($conn->query($sql) === TRUE) {
			  //echo "Record updated successfully";
			} else {
			  //echo "Error updating record: " . $conn->error;
			}
		}
	} else {
	  //echo "0 results";
	    // If user doesn't exist, create record for balance
		$sql = "INSERT INTO balances (user_id, balance, project_id)
		VALUES ('".$user_id."', '".$score."', '".$_SESSION['userData']['project_id']."')";

		if ($conn->query($sql) === TRUE) {
		  //echo "New record created successfully";
		} else {
		  //echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

// Log a specific user credit for a game result
function logCredit($conn, $user_id, $result_id, $amount) {
	$sql = "INSERT INTO transactions (type, user_id, result_id, amount, project_id)
	VALUES ('credit', '".$user_id."', '".$result_id."', '".$amount."', '".$_SESSION['userData']['project_id']."')";

	if ($conn->query($sql) === TRUE) {
	  //echo "New record created successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

// Log a specific user debit for an item purchase
function logDebit($conn, $user_id, $item_id, $amount) {
	$sql = "INSERT INTO transactions (type, user_id, item_id, amount, project_id)
	VALUES ('debit', '".$user_id."', '".$item_id."', '".$amount."', '".$_SESSION['userData']['project_id']."')";

	if ($conn->query($sql) === TRUE) {
	  //echo "New record created successfully";
	} else {
	  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

function transactionHistory($conn) {
	if(isset($_SESSION['userData']['user_id'])){
		$sql = "SELECT transactions.type, amount, items.name, transactions.date_created, results.game_id AS game_id, results.score AS score FROM transactions LEFT JOIN results ON transactions.result_id=results.id LEFT JOIN items ON transactions.item_id = items.id  WHERE transactions.user_id='".$_SESSION['userData']['user_id']."' AND transactions.project_id = '".$_SESSION['userData']['project_id']."' ORDER BY date_created DESC";
		$result = $conn->query($sql);
	
		echo "<table cellspacing='0' id='transactions'><tr><th>Date</th><th>Time</th><th align='center'>Type</th><th align='center'>\$".evaluateText("SCRIP")."</th><th align='center'>Icon</th><th>Description</th><th align='center'>Game</th><th align='center'>Score</th></tr>";
	  	$xp = "<img class='icon' src='icons/xp.png'/>";
		$scrip = "<img class='icon' src='icons/scrip.png'/>";
		while($row = $result->fetch_assoc()) {
			if($row["amount"] == "0" && isset($row["game_id"])){
				echo "<tr class='winner'>";
			} else {
				echo "<tr class='".$row["type"]."'>";
			}
			$date = date("n-j-Y",strtotime("-1 hour", strtotime($row["date_created"])));
			$time = date("g:ia",strtotime("-1 hour", strtotime($row["date_created"])));
			if ($row["type"] == "credit"){
	    		echo "<td>".$date."</td><td>".$time."</td><td align='center'>".ucfirst($row["type"])."</td><td align='center'>".$row["amount"]."</td><td align='center'>";
				echo ($row["amount"] == "0")?$xp:$scrip;
				echo "</td><td>";
				echo ($row["amount"] == "0") ? "Winner" : "Reward";
				echo "</td><td align='center'>".$row["game_id"]."</td><td align='center'>".$row["score"]."</td>";
			}else if ($row["type"] == "debit"){
				echo "<td>".$date."</td><td>".$time."</td><td align='center'>".ucfirst($row["type"])."</td><td align='center'>".$row["amount"]."</td><td align='center'><img class='icon' src='icons/".evaluateText(strtolower(str_replace(" ", "-", $row["name"]))).".png'/></td><td>".evaluateText($row["name"])."</td><td>&nbsp;</td><td>&nbsp;</td>";
			}
			echo "</tr>";
	  	}
		echo "</table>";
	}
}

// Check a specific user balance
function checkBalance($conn) {
	if(isset($_SESSION['userData']['user_id'])){
		$sql = "SELECT user_id, balance FROM balances WHERE user_id='".$_SESSION['userData']['user_id']."' AND project_id = '".$_SESSION['userData']['project_id']."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				return $row["balance"];
			}
		} else {
		  //echo "0 results";
		  	return 0;
		}
	}else{
		return 0;
	}
}

// Admin function to create a new game
function createGame($conn, $name, $prizes) {
	$sql = "INSERT INTO games (name, prizes, active, project_id)
	VALUES ('".$name."','".$prizes."', '1', '".$_SESSION['userData']['project_id']."')";

	if ($conn->query($sql) === TRUE) {
		  //echo "New record created successfully";
		checkGame($conn);
	} else {
		  //echo "Error: " . $sql . "<br>" . $conn->error;
	}
}
?>
