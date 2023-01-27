<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!$_SESSION['logged_in']){
  header('Location: error.php');
  exit();
}
extract($_SESSION['userData']);
//print_r($_SESSION['userData']);
//print_r($_POST);
//exit();

$avatar_url = "https://cdn.discordapp.com/avatars/$discord_id/$avatar.jpg";
/*
$guilds = $_SESSION['userData']['guilds'];
$guildMarkup='';

foreach ($guilds as $key => $guildData) {
    $guildMarkup.='<li class="py-2 px-4 w-full rounded-t-lg border-b border-gray-200 dark:border-gray-600">'.$guildData['name'].'</li>';
}
*/

// Project initialization, had to move from dashboard because of so many dependencies on project id
$project_id = "";
$project_id_changed = "false";
if(isset($_POST['project_id'])){
	$project_id = $_POST['project_id'];
	if($project_id != $_SESSION['userData']['project_id']){
		$project_id_changed = "true";
	}
	$_SESSION['userData']['project_id'] = $project_id;
}else if(!isset($_SESSION['userData']['project_id'])){
	$_SESSION['userData']['project_id'] = 1;
	$project_id = 1;
}

// Handle accept battle request to override game functionality
if(isset($_POST['battle_id'])) {
	// Need to check once again if player has enough currency to prevent hacking HTML form
	$_SESSION["userData"]["battle_id"] = $_POST['battle_id'];
	if(isset($_POST['opponent_id'])) {
		$_SESSION["userData"]["opponent_id"] = $_SESSION['userData']['user_id'];
	}else if(isset($_POST['creator_id'])) {
		$_SESSION["userData"]["creator_id"] = $_SESSION['userData']['user_id'];
	}else{
		// Delete battle and return wager to creator
		deleteBattle($conn, $_POST['battle_id']);
	}
}

// Initiate variables
$roles = $_SESSION['userData']['roles'];
$roleMarkup = '<ul>';

$dropship = 'false';
$patron = 'false';
$vip = 'false';
$heavy = 'false';
$medium = 'false';
$light = 'false';
$base = 'false';
$melee = 'false';
$demolition = 'false';
$extralife = 'false';
/*
$_SESSION['userData']['medkit'] = "false";
$_SESSION['userData']['melee'] = "false";
$_SESSION['userData']['demolition'] = "false";
$_SESSION['userData']['heavy_armor'] = "false";
$_SESSION['userData']['medium_armor'] = "false";
$_SESSION['userData']['light_armor'] = "false";
$_SESSION['userData']['base_armor'] = "false";
*/
$meleeused = 'false';
$demolitionused = 'false';
$extralifeused = 'false';
$dead = 'false';
$dropshipMarkup = '';
$counter = 0;
$features = 0;

enum Weapon: int
{
	case None 			= 0;
	case TacticalKatana = 1;
	case SniperRifle 	= 2;
	case Grenade 		= 3;
	case SmokeBomb 		= 4;
	case MachineGun 	= 5;
	case FlameThrower 	= 6;
	case RocketLauncher = 7;
}
$enuWeapon = Weapon::None;

$MeleeDelay 	= 1;
$DemoDelay 		= 2;

$KatanaDelay	= 1;
$SniperDelay	= 1;
$GrenadeDelay	= 2;
$SmokeBombDelay	= 2;
$MachineGunDelay= 3;
$FlameDelay		= 4;
$RocketDelay	= 5;

// Setting default attribute variables from wallet session data instead of Discord roles
// Using alternate names so it doesn't conflict with inventory session variables

/* Commenting this out to try and assign this data from a squad
if(isset($_SESSION['userData']['heavy'])){
    $heavy = 'true';
	$features = $features + 2;
}
if(isset($_SESSION['userData']['medium'])){
	$medium = 'true';
	$features = $features + 4;
}
if(isset($_SESSION['userData']['light'])){
	$light = 'true';
	$features = $features + 8;
}
if(isset($_SESSION['userData']['base'])){
	$base = 'true';
	$features = $features + 16;
}
if(isset($_SESSION['userData']['extralife'])){
	$extralife = 'true';
	$features = $features + 128;
}
if(isset($_SESSION['userData']['demo'])){
	$demolition = 'true';
	$features = $features + 64;
}
if(isset($_SESSION['userData']['knife'])){
	$melee = 'true';
	$features = $features + 32;
}*/

// Populate role information for display
$roleClass = "role";
//Commenting most of this section out to try loading from wallet instead of discord
foreach ($roles as $key => $roleData) {
	switch ($roleData) {
	  case "1035932493339164703":
		$dropship = 'true';
		$features = $features + 1;
		break;
	  case "984224392114495539":
		$patron = 'true';
		break;
	  case "966399108011163678":
		$vip = "true";
		$_SESSION["userData"]["VIP"] = true;
		break;
	  /*
	  case "984226424275755018":
	    $heavy = 'true';
		$features = $features + 2;
	    break;
	  case "984226602638528532":
	    $medium = 'true';
		$features = $features + 4;
	    break;
	  case "984226701603135488":
	    $light = 'true';
		$features = $features + 8;
	    break;
	  case "1036398693731012618":
	    $base = 'true';
		$features = $features + 16;
	    break;
	  case "1036399378375651410":
	    $melee = 'true';
		$features = $features + 32;
	    break;	
	  case "1036399462555320435":
	    $demolition = 'true';
		$features = $features + 64;
	    break;
	  case "984226800492236870":
	    $extralife = 'true';
		$features = $features + 128;
	    break;*/
	  default:
		/*
		$dropship = 'false';
		$heavy = 'false';
		$medium = 'false';
		$light = 'false';
		$base = 'false';
		$melee = 'false';
		$demolition = 'false';
		$extralife = 'false';
		$features = 0;*/
		break;
	}
}

// Prevent riff raff from getting into the game and adding records to the db, send them to buy the NFTs
/*
if ($features == 0){
	header("Location: https://www.jpg.store/collection/dropship");
	die;
}*/
//if($dropship == "true") { Redundant as people without dropship will not get here
	//$roleMarkup.='<li class="'.$roleClass.'"><img class="icon" src="icons/dropship.png"/>'."<table width='100%'><tr><td><strong>Drop Ship</strong></td><td align='right'>&nbsp;[".(($dropship == "true") ? "&nbsp;✓&nbsp;" : "&nbsp;&nbsp;&nbsp;").']</td></tr></table></li>';
//}
if($heavy == "true" && $medium == "true" && $light == "true" && $base == "true" && $extralife == "true" && $demolition == "true" && $melee == "true") {
	$roleMarkup.='<li class="'.$roleClass.'"><img class="icon" src="icons/supersoldier.png"/>'."<strong>Super Soldier</strong></li>";
}	
if($heavy == "true") {
//if(($features && 3 )== 3) {
	//$roleMarkup.='<li class="'.$roleClass.'"><img class="icon" src="icons/heavy-armor.png"/>'."<table width='100%'><tr><td><strong>Heavy Armor</strong></td><td align='right'>&nbsp;[".(($heavy == "true") ? "&nbsp;✓&nbsp;" : "&nbsp;&nbsp;&nbsp;").']</td></tr></table></li>';
}
if($medium == "true") {
//if(($features && 7 )== 5) {
	//$roleMarkup.='<li class="'.$roleClass.'"><img class="icon" src="icons/medium-armor.png"/>'."<table width='100%'><tr><td><strong>Medium Armor</strong></td><td align='right'>&nbsp;[".(($medium == "true") ? "&nbsp;✓&nbsp;" : "&nbsp;&nbsp;&nbsp;").']</td></tr></table></li>';
}
if($light == "true") {
//if(($features && 15 )== 9) {
	//$roleMarkup.='<li class="'.$roleClass.'"><img class="icon" src="icons/light-armor.png"/>'."<table width='100%'><tr><td><strong>Light Armor</strong></td><td align='right'>&nbsp;[".(($light == "true") ? "&nbsp;✓&nbsp;" : "&nbsp;&nbsp;&nbsp;").']</td></tr></table></li>';
}
if($base == "true") {
//if(($features && 31 )== 17) {
	//$roleMarkup.='<li class="'.$roleClass.'"><img class="icon" src="icons/base-armor.png"/>'."<table width='100%'><tr><td><strong>Base Armor</strong></td><td align='right'>&nbsp;[".(($base == "true") ? "&nbsp;✓&nbsp;" : "&nbsp;&nbsp;&nbsp;").']</td></tr></table></li>';
}
if($extralife == "true") {
	//$roleMarkup.='<li class="'.$roleClass.'"><img class="icon" src="icons/medkit.png"/>'."<table width='100%'><tr><td><strong>Medkit</strong></td><td align='right'>&nbsp;[".(($extralife == "true") ? "&nbsp;✓&nbsp;" : "&nbsp;&nbsp;&nbsp;").']</td></tr></table></li>';
}
if($demolition == "true") {
	//$roleMarkup.='<li class="'.$roleClass.'"><img class="icon" src="icons/demolition.png"/>'."<table width='100%'><tr><td><strong>Demolition</strong></td><td align='right'>&nbsp;[".(($demolition == "true") ? "&nbsp;✓&nbsp;" : "&nbsp;&nbsp;&nbsp;").']</td></tr></table></li>';
}
if($melee == "true") {
	//$roleMarkup.='<li class="'.$roleClass.'"><img class="icon" src="icons/melee.png"/>'."<table width='100%'><tr><td><strong>Melee</strong></td><td align='right'>&nbsp;[".(($melee == "true") ? "&nbsp;✓&nbsp;" : "&nbsp;&nbsp;&nbsp;").']</td></tr></table></li>';	
}
//$roleMarkup .= '</ul>';

// Also extracted this from dashboard to support multi page
$filterby = "";
if(isset($_POST['filterby'])){
	$filterby = $_POST['filterby'];
}

// Extracted this function from dashboard so that it can be used on other pages
function filterTroops($page){
	$barracks = "";
	if($page == "dashboard"){
		$barracks = "#barracks";
	}
	echo'
	<div id="filter-troops">
		<label for="filterTroops"><strong>Filter By:</strong></label>
		<select onchange="javascript:filterTroops(this.options[this.selectedIndex].value);" name="filterTroops" id="filterTroops">
			<option value="None">Attribute</option>
			<option value="None">All</option>
			<option value="Heavy">'.evaluateText("Heavy").'</option>
			<option value="Medium">'.evaluateText("Medium").'</option>
			<option value="Light">'.evaluateText("Light").'</option>
			<option value="Base">'.evaluateText("Base").'</option>
			<option value="Medkit">'.evaluateText("Medkit").'</option>
			<option value="Demolition">'.evaluateText("Demolition").'</option>
			<option value="Melee">'.evaluateText("Melee").'</option>
		</select>
		<form id="filterTroopsForm" action="'.$page.'.php'.$barracks.'" method="post">
		  <input type="hidden" id="filterby" name="filterby" value="">
		  <input type="submit" value="Submit" style="display:none;">
		</form>
	</div>';
}

// Call initial DB functions
checkUser($conn);
checkGame($conn);

// Set project folder prefix
$prefix = "/".strtolower(str_replace(" ", "-", getProjectName($conn)))."/";

// Kill player
function kill($dropshipMarkup, $counter) {
	global $prefix;
	$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - DEAD</h3>";
	if($counter == 3){
		$dropshipMarkup.="<img src='".$prefix."images/die/beach.gif?var=123'/></div>";
	}else if($counter >= 4 && $counter <= 7){
		$dropshipMarkup.="<img src='".$prefix."images/die/landmine.gif?var=123'/></div>";
	}else if($counter >= 13){
		$dropshipMarkup.="<img src='".$prefix."images/die/bunker.gif?var=123'/></div>";
	}else{
		$dropshipMarkup.="<img src='".$prefix."images/die/".rand(1,3).".gif?var=123'/></div>";
	}
	$_SESSION['userData']['score'] = strval($counter);
	return $dropshipMarkup;
}

// Variables to store current random scene number, initialized with last GIF number for specific scene
$beach_random = 7;
$hill_random = 6;
$bunker_random = 6;
$beach_exo_random = 2;
$hill_exo_random = 2;
$bunker_exo_random = 2;

// Handle player survival for various scenes
function live($dropshipMarkup, $counter) {
	global $beach_random, $hill_random, $bunker_random, $beach_exo_random, $hill_exo_random, $bunker_exo_random, $prefix;
	if(isset($_SESSION['userData']['exo_suit'])){
		$exo_suit = $_SESSION['userData']['exo_suit'];
	}
	$random = 1;
	if ($counter <= 7) {
		if ($counter != 7) {
			if($exo_suit == "true"){
				$random = verifyRandom(1, 2, $beach_exo_random);
				$beach_exo_random = $random;
				$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Exo Suit")."</h3>";
				$dropshipMarkup.="<img src='".$prefix."images/live/beach/exo/".$random.".gif?var=123'/></div>";
			}else{
				$random = verifyRandom(1, 7, $beach_random);
				$beach_random = $random;
				$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - Survived</h3>";
				$dropshipMarkup.="<img src='".$prefix."images/live/beach/".$random.".gif?var=123'/></div>";
			}
		}else{
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Beach Secured")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/live/beach/beach-secured.gif'/></div>";
		}
	} else if ($counter <= 12) {
		if ($counter != 12) {
			if($exo_suit == "true"){
				$random = verifyRandom(1, 2, $hill_exo_random);
				$hill_exo_random = $random;
				$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Exo Suit")."</h3>";
				$dropshipMarkup.="<img src='".$prefix."images/live/hill/exo/".$random.".gif?var=123'/></div>";
			}else{
				$random = verifyRandom(1, 6, $hill_random);
				$hill_random = $random;
				$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - Survived</h3>";
				$dropshipMarkup.="<img src='".$prefix."images/live/hill/".$random.".gif'/></div>";
			}
		}else{
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Hill Secured")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/live/hill/hill-secured.gif'/></div>";
		}
	} else {
		if($exo_suit == "true"){
			$random = verifyRandom(1, 2, $bunker_exo_random);
			$bunker_exo_random = $random;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Exo Suit")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/live/bunker/exo/".$random.".gif?var=123'/></div>";
		}else{
			if($counter < 16){
				$random = verifyRandom(1, 6, $bunker_random);
			}else{
				$random = verifyRandom(7, 12, $bunker_random);
			}
			$bunker_random = $random;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - Survived</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/live/bunker/".$random.".gif'/></div>";
		}
	}
	return $dropshipMarkup;
}

// Ensure every scene does not repeat a random GIF
function verifyRandom($min, $max, $previous_random){
	$random = rand($min, $max);
	while($random == $previous_random){
		$random = rand($min, $max);
	}
	return $random;
}

// Handle player melee sequence
function melee($dropshipMarkup, $counter) {
	global $prefix;
	$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Melee")."</h3>";
	$dropshipMarkup.="<img src='".$prefix."images/weapons/melee.gif'/></div>";
	return $dropshipMarkup;
}

// Handle player demolition sequence
function demolition($dropshipMarkup, $counter) {
	global $prefix;
	$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Demolition")."</h3>";
	$dropshipMarkup.="<img src='".$prefix."images/weapons/demo1.gif'/></div>";
	$counter++;
	$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Explosion")."</h3>";
	$dropshipMarkup.="<img src='".$prefix."images/weapons/demo2.gif'/></div>";
	return $dropshipMarkup;
}

function weapons($dropshipMarkup, $counter, $enuWeapon) {
	global $prefix;
	Switch($enuWeapon){
		case Weapon::TacticalKatana:
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Tactical Katana")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/tactical-katana.gif?var=123'/></div>";
			$_SESSION['userData']['tactical_katana'] = false;
			break;
		case Weapon::SniperRifle:
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Sniper Rifle")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/sniper-rifle.gif?var=123'/></div>";
			$_SESSION['userData']['sniper_rifle'] = false;
			break;
		case Weapon::Grenade:
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Pull Grenade Pin")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/grenade1.gif?var=123'/></div>";
			$counter++;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Throw Grenade")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/grenade2.gif?var=123'/></div>";
			$_SESSION['userData']['grenade'] = false;
			break;
		case Weapon::SmokeBomb:
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Pull Smoke Bomb Pin")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/smoke-bomb1.gif?var=123'/></div>";
			$counter++;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Smoke Bomb")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/smoke-bomb2.gif?var=123'/></div>";
			$_SESSION['userData']['smoke_bomb'] = false;
			break;
		case Weapon::MachineGun:
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Load Machine Gun")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/machine-gun1.gif'/></div>";
			$counter++;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Machine Gun")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/machine-gun2.gif'/></div>";
			$counter++;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Machine Gun")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/machine-gun3.gif'/></div>";
			$_SESSION['userData']['machine_gun'] = false;
			break;
		case Weapon::FlameThrower:
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Flamethrower Ignition")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/flamethrower1.gif?var=123'/></div>";
			$counter++;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Flamethrower Spray")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/flamethrower2.gif?var=123'/></div>";
			$counter++;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Flamethrower Flames")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/flamethrower3.gif?var=123'/></div>";
			$counter++;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Flamethrower Fire")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/flamethrower4.gif?var=123'/></div>";
			$_SESSION['userData']['flamethrower'] = false;
			break;
		case Weapon::RocketLauncher:
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Load Rocket Launcher")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/rocket-launcher1.gif'/></div>";
			$counter++;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Rocket Launcher Gunfire")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/rocket-launcher2.gif'/></div>";
			$counter++;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Rocket Launcher Gunfire")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/rocket-launcher3.gif'/></div>";
			$counter++;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Rocket Launcher")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/rocket-launcher4.gif'/></div>";
			$counter++;
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Rocket Launcher Explosion")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/weapons/rocket-launcher5.gif'/></div>";
			$_SESSION['userData']['rocket_launcher'] = false;
			break;
		default:
			break;
	}
	return $dropshipMarkup;
}

function weapondelay($counter, $enuWeapon)	{
	global $KatanaDelay, $SniperDelay, $GrenadeDelay, $SmokeBombDelay, $MachineGunDelay, $FlameDelay, $RocketDelay;
	Switch($enuWeapon){
		case Weapon::TacticalKatana:
			$counter = $counter + $KatanaDelay;
			break;
		case Weapon::SniperRifle:
			$counter = $counter + $SniperDelay;
			break;
		case Weapon::Grenade:
			$counter = $counter + $GrenadeDelay;
			break;
		case Weapon::SmokeBomb:
			$counter = $counter + $SmokeBombDelay;
			break;
		case Weapon::MachineGun:
			$counter = $counter + $MachineGunDelay;
			break;
		case Weapon::FlameThrower:
			$counter = $counter + $FlameDelay;
			break;
		case Weapon::RocketLauncher:
			$counter = $counter + $RocketDelay;
			break;
		default:
			break;
	}
	return $counter;
}

function setWeapons($enuWeapon){
	$enuWeapon = Weapon::None;
	if($_SESSION['userData']['rocket_launcher'] == "true"){
		$rocket_launcher = $_SESSION['userData']['rocket_launcher'];
		if($rocket_launcher == "true"){
			$enuWeapon = Weapon::RocketLauncher;
		}
	}
	if($_SESSION['userData']['flamethrower'] == "true"){
		$flamethrower = $_SESSION['userData']['flamethrower'];
		if($flamethrower == "true"){
			$enuWeapon = Weapon::FlameThrower;
		}
	}
	if($_SESSION['userData']['machine_gun'] == "true"){
		$machine_gun = $_SESSION['userData']['machine_gun'];
		if($machine_gun == "true"){
			$enuWeapon = Weapon::MachineGun;
		}
	}
	if($_SESSION['userData']['smoke_bomb'] == "true"){
		$machine_gun = $_SESSION['userData']['smoke_bomb'];
		if($machine_gun == "true"){
			$enuWeapon = Weapon::SmokeBomb;
		}
	}
	if($_SESSION['userData']['grenade'] == "true"){
		$grenade = $_SESSION['userData']['grenade'];
		if($grenade == "true"){
			$enuWeapon = Weapon::Grenade;
		}
	}
	if($_SESSION['userData']['sniper_rifle'] == "true"){
		$sniper_rifle = $_SESSION['userData']['sniper_rifle'];
		if($sniper_rifle == "true"){
			$enuWeapon = Weapon::SniperRifle;
		}
	}
	if(isset($_SESSION['userData']['tactical_katana'])){
		$tactical_katana = $_SESSION['userData']['tactical_katana'];
		if($tactical_katana == "true"){
			$enuWeapon = Weapon::TacticalKatana;
		}
	}
	return $enuWeapon;
}

// Handle player's extra life or lack of an extra life
function extralife($dropshipMarkup, $counter, $extralife, $dead, $melee, $demolition, $dropbox) {
	global $MeleeDelay, $DemoDelay, $enuWeapon, $prefix;
	$tactical_katana = "false";
	$sniper_rifle = "false";
	$grenade = "false";
	$machine_gun = "false";
	$flamethrower = "false";
	$rocket_launcher = "false";
	$enuWeapon = setWeapons($enuWeapon);
	if ($extralife == 'true' || $_SESSION['userData']['medkit'] == "true") {
		$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - Extra Life</h3>";
		$dropshipMarkup.="<img src='".$prefix."images/1up/".rand(1,2).".gif?var=123'/></div>";
		$extralife = 'false';
		$_SESSION['userData']['medkit'] = "false";
	} else {
		if ($melee == "true" || $_SESSION['userData']['melee'] == "true") {
			$dropshipMarkup = melee($dropshipMarkup, $counter);
			$melee = "false";
			$_SESSION['userData']['melee'] = "false";
			$counter = $counter + $MeleeDelay;
			if($demolition == "true" || $_SESSION['userData']['demolition'] == "true"){
				$dropshipMarkup = demolition($dropshipMarkup, $counter);
				$demolition = "false";
				$_SESSION['userData']['demolition'] = "false";
				$counter = $counter + $DemoDelay;
				$dropshipMarkup = weapons($dropshipMarkup, $counter, $enuWeapon);
				$counter=weapondelay($counter, $enuWeapon);
				if($dropbox == true){
					$enuWeapon = setWeapons($enuWeapon);
					$dropshipMarkup = weapons($dropshipMarkup, $counter, $enuWeapon);
					$counter=weapondelay($counter, $enuWeapon);	
				}
				$dropshipMarkup = kill($dropshipMarkup, $counter);
				$dead = 'true';
			} else {
			    $dropshipMarkup = weapons($dropshipMarkup, $counter, $enuWeapon);
				$counter=weapondelay($counter, $enuWeapon);
				if($dropbox == true){
					$enuWeapon = setWeapons($enuWeapon);
					$dropshipMarkup = weapons($dropshipMarkup, $counter, $enuWeapon);
					$counter=weapondelay($counter, $enuWeapon);	
				}
				$dropshipMarkup = kill($dropshipMarkup, $counter);
				$dead = 'true';
			}
		} else if ($demolition == "true" || $_SESSION['userData']['demolition'] == "true") {
			$dropshipMarkup = demolition($dropshipMarkup, $counter);
			$demolition = "false";
			$_SESSION['userData']['demolition'] = "false";
			$counter = $counter + $DemoDelay;
			$dropshipMarkup = weapons($dropshipMarkup, $counter, $enuWeapon);
			$counter=weapondelay($counter, $enuWeapon);
			if($dropbox == true){
				$enuWeapon = setWeapons($enuWeapon);
				$dropshipMarkup = weapons($dropshipMarkup, $counter, $enuWeapon);
				$counter=weapondelay($counter, $enuWeapon);	
			}
			$dropshipMarkup = kill($dropshipMarkup, $counter);
			$dead = 'true';
		} else {
			$dropshipMarkup = weapons($dropshipMarkup, $counter, $enuWeapon);
			$counter=weapondelay($counter, $enuWeapon);
			if($dropbox == true){
				$enuWeapon = setWeapons($enuWeapon);
				$dropshipMarkup = weapons($dropshipMarkup, $counter, $enuWeapon);
				$counter=weapondelay($counter, $enuWeapon);	
			}
			$dropshipMarkup = kill($dropshipMarkup, $counter);
			$dead = 'true';
		}	
	}
	$results = array();
	$results['extralife'] = strval($extralife);
	$results['dead'] = strval($dead);
	$results['markup'] = $dropshipMarkup;
	$results['counter'] = $counter;
	$results['melee'] = $melee;
	$results['demolition'] = $demolition;
	return $results;
}

// Handle night vision goggles for bunker
function nightvisiongoggles($dropshipMarkup, $counter) {
	global $prefix;
	$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Night Vision Goggles")."</h3>";
	$dropshipMarkup.="<img src='".$prefix."images/equipment/night-vision-goggles.gif?var=123'/></div>";
	return $dropshipMarkup;
}

// Handle radar for hill
function radar($dropshipMarkup, $counter) {
	global $prefix;
	$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Radar")."</h3>";
	$dropshipMarkup.="<img src='".$prefix."images/equipment/radar.gif?var=123'/></div>";
	return $dropshipMarkup;
}

// Handle radio for beach or hill
function radio($dropshipMarkup, $counter) {
	global $prefix;
	$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Radio")."</h3>";
	$dropshipMarkup.="<img src='".$prefix."images/equipment/radio.gif?var=123'/></div>";
	$counter++;
	// Have to account for using radio at tail end of beach, that is why counter is evaluating 8
	if ($counter <= 8) {
		$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Reinforcements")."</h3>";
		$dropshipMarkup.="<img src='".$prefix."images/equipment/reinforcements.gif?var=123'/></div>";
	// Have to account for using radio at tail end of hill, that is why counter is evaluating 13
	} else if ($counter <= 13) {
		$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Pilot")."</h3>";
		$dropshipMarkup.="<img src='".$prefix."images/equipment/airstrike1.gif?var=123'/></div>";
		$counter++;
		$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Air Strike")."</h3>";
		$dropshipMarkup.="<img src='".$prefix."images/equipment/airstrike2.gif?var=123'/></div>";
	} 
	return $dropshipMarkup;
}

// Handle jetpack for beach or hill
function jetpack($dropshipMarkup, $counter) {
	global $prefix;
	$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Jet Pack Takeoff")."</h3>";
	$dropshipMarkup.="<img src='".$prefix."images/equipment/jet-pack1.gif'/></div>";
	$counter++;
	$jetpackcounter = 2;
	// Have to account for using jetpack at tail end of beach, that is why counter is evaluating 8
	if ($counter <= 8) {
		if($counter == 8){
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Jet Pack Fly")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/equipment/jet-pack".$jetpackcounter.".gif?var=123'/></div>";
			$counter++;
		}
		while($counter <= 7){
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Jet Pack Fly")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/equipment/jet-pack".$jetpackcounter.".gif?var=123'/></div>";
			$counter++;
			$jetpackcounter++;
		}
	// Have to account for using jetpack at tail end of hill, that is why counter is evaluating 13
	} else if ($counter <= 13) {
		if($counter == 13){
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Jet Pack Fly")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/equipment/jet-pack".$jetpackcounter.".gif?var=123'/></div>";
			$counter++;
		}
		while($counter <= 12){
			$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Jet Pack Fly")."</h3>";
			$dropshipMarkup.="<img src='".$prefix."images/equipment/jet-pack".$jetpackcounter.".gif?var=123'/></div>";
			$counter++;
			$jetpackcounter++;
		}
	} 
	return $dropshipMarkup;
}

// Handle dropbox for beach or hill
function dropbox($dropshipMarkup, $counter, $items, $melee, $demolition, $conn) {
	global $prefix;
	$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Drop Box")."</h3>";
	$dropshipMarkup.="<img src='".$prefix."images/equipment/drop-box.gif'/></div>";
	$counter++;
	
	$_SESSION['userData']['melee'] = $melee;
	$_SESSION['userData']['demolition'] = $demolition;
	
	$random_index = rand(1, count($items));
	// Get a random index that is false
	while($_SESSION['userData'][$items[$random_index]] == "true"){
		$random_index = rand(1, count($items));
	}
	$_SESSION['userData'][$items[$random_index]] = "true";
	
	// Convert session item name format to db format
	$item_name = "";
	$item_names = explode("_", $items[$random_index]);
	foreach($item_names AS $name){
		$item_name .= ucfirst($name)." ";
	}
	
	$dropshipMarkup.="<div class='round' id='".$counter."'><h3>Round ".$counter." - ".evaluateText("Drop Box")." - ".evaluateText(trim($item_name))."</h3>";
	$dropshipMarkup.="<img src='icons/".evaluateText(str_replace("_", "-", $items[$random_index])).".png'/></div>";
	
	// Lookup item id based on reformatted item name
	$item_id = checkItemID ($conn, trim($item_name));
	
	// Purchase drop box weapon item for no cost and pass true flag to indicate it's a drop box transaction
	buyItem ($conn, $item_id, true);
	
	return $dropshipMarkup;
}

$currentScoreMarkup = "";

// Run Drop Ship
if(isset($_POST['run'])){
	// Check for active game OR active battle
	if(isset($_SESSION['userData']['game_id']) || isset($_SESSION['userData']['battle_id'])) {
		checkScore($conn);
		$squadTotal = checkSquadCount($conn);
		// Check if there is no current score and a squad is formed OR a battle is accepted and a squad is formed
		if((!isset($_SESSION['userData']['current_score']) && $squadTotal >= 1) || (isset($_SESSION['userData']['battle_id']) && $squadTotal >= 1)) {
			// Populate global NFT attribute variables based on squad
			setSquad($conn);
			// If there is no battle, remove current score
			if(!isset($_SESSION['userData']['battle_id'])){
				unset($_SESSION['userData']['current_score']);
			}
			$threshold = 0;
			$ballistic_shield = "false";
			$quantum_stealth = "false";
			$exo_suit = "false";
			$mech_suit = "false";
			if(isset($_SESSION['userData']['ballistic_shield'])){
				$ballistic_shield = $_SESSION['userData']['ballistic_shield'];
			}
			if(isset($_SESSION['userData']['quantum_stealth'])){
				$quantum_stealth = $_SESSION['userData']['quantum_stealth'];
			}
			if(isset($_SESSION['userData']['exo_suit'])){
				$exo_suit = $_SESSION['userData']['exo_suit'];
			}
			if(isset($_SESSION['userData']['mech_suit'])){
				$mech_suit = $_SESSION['userData']['mech_suit'];
			}
			
			$heavy_threshold = 74;
			$medium_threshold = 72;
			$light_threshold = 65.66;
			$base_thresold = 50;
			$medium_credit = 3;
			$light_credit = 2;
			$base_credit = 1;
			
			if($mech_suit == 'true') {
				$threshold = 88.9;
			} else if($exo_suit == 'true') {
				$threshold = 87.5;
			} else if($quantum_stealth == 'true') {
				$threshold = 85.7;
			} else if($ballistic_shield == 'true') {
				$threshold = 83.3;
			} else if($heavy == 'true' || $_SESSION['userData']['heavy_armor'] == "true") {
				if($medium == 'true' || $_SESSION['userData']['medium_armor'] == "true") {
					if($light == "true" || $_SESSION['userData']['light_armor'] == "true"){
						if($base == "true" || $_SESSION['userData']['base_armor'] == "true"){
							$threshold = $heavy_threshold+$medium_credit+$light_credit+$base_credit;
						}else{
							$threshold = $heavy_threshold+$medium_credit+$light_credit;
						}
					}else if($base == "true" || $_SESSION['userData']['base_armor'] == "true"){
						$threshold = $heavy_threshold+$medium_credit+$base_credit;
					}else{
						$threshold = $heavy_threshold+$medium_credit;
					}
				}else if($light == "true" || $_SESSION['userData']['light_armor'] == "true"){
					if($base == "true" || $_SESSION['userData']['base_armor'] == "true"){
						$threshold = $heavy_threshold+$light_credit+$base_credit;
					}else{
						$threshold = $heavy_threshold+$light_credit;
					}
				}else if($base == "true" || $_SESSION['userData']['base_armor'] == "true"){
					$threshold = $heavy_threshold+$base_credit;
				}else{
					$threshold = $heavy_threshold;
				}
			} else if($medium == 'true' || $_SESSION['userData']['medium_armor'] == "true") {
				if($light == "true" || $_SESSION['userData']['light_armor'] == "true"){
					if($base == "true" || $_SESSION['userData']['base_armor'] == "true"){
						$threshold = $medium_threshold+$light_credit+$base_credit;
					}else{
						$threshold = $medium_threshold+$light_credit;
					}
				}else if($base == "true" || $_SESSION['userData']['base_armor'] == "true"){
					$threshold = $medium_threshold+$base_credit;
				}else{
					$threshold = $medium_threshold;
				}
			} else if($light == 'true' || $_SESSION['userData']['light_armor'] == "true") {
				if($base == "true" || $_SESSION['userData']['base_armor'] == "true"){
					$threshold = $light_threshold+$base_credit;
				}else{
					$threshold = $light_threshold;
				}
			} else if($base == 'true' || $_SESSION['userData']['base_armor'] == "true") {
				$threshold = $base_thresold;
			}
			$dropbox = false;
			// Original loop logic checking drop ship flag for drop ship holders, which prevents non-dropship holders, like Discos, from playing
			//while($dead == 'false' && $dropship == 'true') {
			while($dead == 'false') {
				if ($counter <= 2){
					if ($counter == 0){
						$dropshipMarkup.="<div class='round' id='0'><h3>".evaluateText("Drop Ship Initiating")."</h3>";
						$dropshipMarkup.="<img src='".$prefix."images/reset/reset.gif?var=123'/></div>";
					}
					if ($counter == 1){
						$dropshipMarkup.="<div class='round' id='1'><h3>Round 1 - ".evaluateText("Drop Ship")."</h3>";
						$dropshipMarkup.="<img src='".$prefix."images/drop/drop.gif?var=123'/></div>";
					}
					if ($counter == 2){
						$dropshipMarkup.="<div class='round' id='2'><h3>Round 2 - ".evaluateText("Drop Ship Landed")."</h3>";
						$dropshipMarkup.="<img src='".$prefix."images/drop/team.gif?var=123'/></div>";
					}
					$counter++;
				} else {
					$probability = rand(0,99);
					if ($probability < $threshold) {
						if($counter == 8 && $_SESSION['userData']['radar'] == "true"){
							$dropshipMarkup = radar($dropshipMarkup, $counter);
						}else if($counter == 13 && $_SESSION['userData']['night_vision_goggles'] == "true"){
							$dropshipMarkup = nightvisiongoggles($dropshipMarkup, $counter);
						}else{
							$dropshipMarkup = live($dropshipMarkup, $counter);
						}
					} else {
						// Drop Box item
						if($counter <= 12 && $_SESSION['userData']['drop_box'] == "true"){
							$items = getDropBoxItems($conn);
							$dropshipMarkup = dropbox($dropshipMarkup, $counter, $items, $melee, $demolition, $conn);
							$_SESSION['userData']['drop_box'] = "false";
							$dropbox = true;
							$counter = $counter+2;
						}
						// Radio item
						if($counter <= 12 && $_SESSION['userData']['radio'] == "true"){
							$dropshipMarkup = radio($dropshipMarkup, $counter);
							$_SESSION['userData']['radio'] = "false";
							if($counter <= 7){
								$counter = $counter+2;
							}else if($counter <= 12){
								$counter = $counter+3;
							}
						}
						// Jet Pack item
						if($counter <= 12 && $_SESSION['userData']['jet_pack'] == "true"){
							$dropshipMarkup = jetpack($dropshipMarkup, $counter);
							$_SESSION['userData']['jet_pack'] = "false";
							$difference = 0;
							// Account for beach use
							if($counter <= 7){
								$difference = 7-$counter;
								if($difference == 0){
									$difference = 1;
								}
								$counter = $difference+$counter;
							// Account for hill use
							}else if($counter <= 12 && $counter > 7){
								$difference = 12-$counter;
								if($difference == 0){
									$difference = 1;
								}
								$counter = $difference+$counter;
							}
							// Prepare counter for extra life sequence
							$counter++;
						}
						// Extra life 
						$results = extralife($dropshipMarkup, $counter, $extralife, $dead, $melee, $demolition, $dropbox);
						$extralife = $results['extralife'];
						$dead = $results['dead'];
						$dropshipMarkup = $results['markup'];
						$counter = $results['counter'];
						$melee = $results['melee'];
						$demolition = $results['demolition'];
					}
					$counter++;
				}	
			}
			if(isset($dropshipMarkup)){
				// Log game score if no battle is active
				if(!isset($_SESSION['userData']['battle_id'])){
					logScore($conn, $dropshipMarkup);
				}else{
					// Log Battle Score (Opponent or Creator)
					if(isset($_SESSION["userData"]["opponent_id"]){
						logBattleScore($conn, "opponent", $_SESSION["userData"]["opponent_id"], $_SESSION["userData"]["battle_id"]);
					}else if(isset($_SESSION["userData"]["creator_id"]){
						logBattleScore($conn, "creator", $_SESSION["userData"]["creator_id"], $_SESSION["userData"]["battle_id"]);
					}
				}
			}else{
				echo "<script type='text/javascript'>alert('Something went wrong with your game and no results markup was generated. Your score was not logged and your squad is still intact. Please try again and let Oculus Orbus know if you continue to experience issues.');</script>";
			}
		}
	}
}

$hideLeaderboard = "false";
?>