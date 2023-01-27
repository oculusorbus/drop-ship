<?php
include 'db.php';
include 'dropship.php';
include 'webhooks.php';
include 'header.php';

// Handle post actions at the top of the page to ensure data is accurate before rendering HTML
// Soldier allocation
if(isset($_POST['soldier_id'])) {
	if($_POST['deploy'] == 1){
		deploySoldier($conn, $_POST['soldier_id'], 1);
	}else{
		deploySoldier($conn, $_POST['soldier_id'], 0);
	}
}
//Item purchases
if(isset($_POST['item_id'])) {
	// Disable purchasing of items if the game has already been played. This is a fallback error in case someone tried to hack the HTML.
	if(!isset($_SESSION['userData']['current_score']) || checkSquadCount($conn) > 0){
		// Buy item and pass false flag since it's not a Drop Box transaction
		buyItem($conn, $_POST['item_id'], false);
	}else{
		?><script type="text/javascript">alert("You've already played Drop Ship. You cannot purchase items until a new game is live.");</script><?php
	}
} 
// Activate / Deactivate games
if($_SESSION['userData']['discord_id'] == "772831523899965440") {
	if(isset($_POST['deactivate']) && isset($_SESSION['userData']['game_id'])) {			
		$title = "Game Over";
		ob_start(); // Start output buffering
		checkLeaderboard($conn, "true");
		$list = ob_get_contents(); // Store buffer in variable
		ob_end_clean(); // End buffering and clean up
		$description = $list;
		$imageurl = "https://www.madballs.net/".$prefix."/images/dropship.jpg";
		discordmsg($title, evaluateText($description), $imageurl);
		deactivateGame($conn);
	}
	if(isset($_POST['newgame']) && !isset($_SESSION['userData']['game_id'])) {
		createGame($conn, $_POST['name'], $_POST['prizes']);
		$title = "New Game ".$_POST['name'];
		$description = "A new game of ".getProjectName($conn)." has been created.";
		$imageurl = "https://www.madballs.net/".$prefix."/images/dropship.jpg";
		discordmsg($title, $description, $imageurl);
	}
}

function renderNFT($nft_data, $ipfs){
	echo "<div class='nft'><div class='nft-data'>
	<span class='nft-name'>".$nft_data->name."</span>";
//	echo "<span class='nft-image'><img src='https://cloudflare-ipfs.com/ipfs/".$ipfs."'/></span>";
	echo "<span class='nft-image'><img src='https://image-optimizer.jpgstoreapis.com/".$ipfs."'/></span>";
	/*
	<span class='nft-rank'><strong>Rank: </strong>".$nft_data->Rank."</span>
	<span class='nft-armor'><strong>Armor: </strong>".$nft_data->Armor."</span>
	<span class='nft-gear'><strong>Gear: </strong>".$nft_data->Gear."</span>
	<span class='nft-level'><strong>Level: </strong>".$nft_data->Level."</span><br><br>*/
	echo "</div></div>";
}

function storeRank($ranks){
	if(isset($ranks)){
		if(isset($ranks["VIP"])){
			$_SESSION['userData']['rank'] = "VIP";
		}else if(isset($ranks["Mayor"])){
			$_SESSION['userData']['rank'] = "Mayor";
		}else if(isset($ranks["Don"])){
			$_SESSION['userData']['rank'] = "Don";
		}else if(isset($ranks["Capo"])){
			$_SESSION['userData']['rank'] = "Capo";
		}else if(isset($ranks["VIP"])){
			$_SESSION['userData']['rank'] = "VIP";
		}else if(isset($ranks["Patron"])){
			$_SESSION['userData']['rank'] = "Patron";
		}else if(isset($ranks["Henchmen"])){
			$_SESSION['userData']['rank'] = "Henchmen";
		}else if(isset($ranks["Command Sergeant Major"])){
			$_SESSION['userData']['rank'] = "Command Sergeant Major";
		}else if(isset($ranks["Sergeant Major"])){
			$_SESSION['userData']['rank'] = "Sergeant Major";
		}else if(isset($ranks["First Sergeant"])){
			$_SESSION['userData']['rank'] = "First Sergeant";
		}else if(isset($ranks["Master Sergeant"])){
			$_SESSION['userData']['rank'] = "Master Sergeant";
		}else if(isset($ranks["Sergeant First Class"])){
			$_SESSION['userData']['rank'] = "Sergeant First Class";
		}else if(isset($ranks["Staff Sergeant"])){
			$_SESSION['userData']['rank'] = "Staff Sergeant";
		}else if(isset($ranks["Sergeant"])){
			$_SESSION['userData']['rank'] = "Sergeant";
		}else if(isset($ranks["Corporal"])){
			$_SESSION['userData']['rank'] = "Corporal";
		}else if(isset($ranks["Specialist"])){
			$_SESSION['userData']['rank'] = "Specialist";
		}else if(isset($ranks["Private"])){
			$_SESSION['userData']['rank'] = "Private";
		}
	}
}

$policy_id = getProjectPolicyId($conn);
$ranks = array();

// Handle wallet changes
$address_changed = "false";
if(!isset($_SESSION['userData']['address'])){
	$address = checkAddress($conn);
	if(isset($address)){
		if($address != ""){
			$_SESSION['userData']['address'] = $address;
			$address_changed = "true";
		}
	}
}

// Handle wallet selection
if(isset($_POST['address'])){
	if(isset($_SESSION['userData']['address'])){
		if($_SESSION['userData']['address'] != $_POST['address']){
			$address_changed = "true";
			updateAddress($conn, $_POST['address']);
		}
	}else{
		$address_changed = "true";
		updateAddress($conn, $_POST['address']);
	}
	$_SESSION['userData']['address'] = $_POST['address'];
	$_SESSION['userData']['wallet'] = $_POST['wallet'];
}

if((isset($_SESSION['userData']['address']) && $address_changed == "true") || $project_id_changed == "true"){
	if($_SESSION['userData']['address'] != ""){
		//echo "<div class='current-address'><strong>Current Address:</strong><br>".$_SESSION['userData']['address']."</div>";
		$ch = curl_init("https://api.koios.rest/api/v0/address_assets");
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, '{"_addresses":["'.$_SESSION['userData']['address'].'"]}');
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec( $ch );
		// If you need to debug, or find out why you can't send message uncomment line below, and execute script.
		$response = json_decode($response);
		//print_r($response[0]->asset_list);
		//exit;
		curl_close( $ch );
	
		$_SESSION['userData']['nfts'] = array();
	    if(isset($response[0])){
			$asset_names = array();
			$counter = 0;
			foreach($response[0]->asset_list AS $index => $token){
				if($token->policy_id == $policy_id){
					$tokench = curl_init("https://api.koios.rest/api/v0/asset_info?_asset_policy=".$token->policy_id."&_asset_name=".$token->asset_name);
					curl_setopt( $tokench, CURLOPT_RETURNTRANSFER, 1);
					$tokenresponse = curl_exec( $tokench );
					$tokenresponse = json_decode($tokenresponse);
					curl_close( $tokench );
					if(isset($tokenresponse[0])){
						foreach($tokenresponse[0]->minting_tx_metadata AS $metadata){
							$counter++;
							$policy_id = $token->policy_id;
							$asset_name = $tokenresponse[0]->asset_name_ascii;
							$nft = $metadata->$policy_id;
							$nft_data = $nft->$asset_name;
							$ipfs = substr($nft_data->image, 7, strlen($nft_data->image));
							if($_SESSION['userData']['project_id'] == 1){
								$ranks[$nft_data->Rank] = true;
							}
							//$armor[$nft_data->Armor] = true;
							//$gear[$nft_data->Gear] = true;
							// Account for NFT with NaN value for asset name
							if($asset_name == "NaN"){
								$nft_data->AssetName = "DROPSHIP012";
							}else{
								$nft_data->AssetName = $asset_name;
							}
							//renderNFT($nft_data, $ipfs);
							/* Removing to rely on database now
							if($_SESSION['userData']['project_id'] != 1){
								$_SESSION['userData']['nfts'][] = $nft_data;
							}*/
							$asset_names[] = $nft_data->AssetName;
							if(!checkSoldier($conn, $nft_data->AssetName)){
								// Soldier doesn't exist, create soldier
								if($_SESSION['userData']['project_id'] != 1){
									$armor_weight["Base"] = 0;
									$armor_weight["Light"] = 2;
									$armor_weight["Medium"] = 4;
									$armor_weight["Heavy"] = 6;
									$gear_weight["None"] = 1;
									$gear_weight["Melee"] = 2;
									$gear_weight["Demolition"] = 3;
									$gear_weight["Medkit"] = 4;
									$armor = array("Heavy", "Medium", "Light", "Base");
									$gear = array("None", "Melee", "Demolition", "Medkit");
									$armor_random = rand(0,3);
									$gear_random = rand(0,3);
									$armor_final = $armor[$armor_random];
									$gear_final = $gear[$gear_random];
									$level = $armor_weight[$armor_final] + $gear_weight[$gear_final];
									if(!isset($nft_data->summary)){
										$nft_data->summary = null;
									}
									if($_SESSION['userData']['project_id'] == 2 || $_SESSION['userData']['project_id'] == 3){
										$rank = "Henchmen";
									}else if($_SESSION['userData']['project_id'] == 4){
										$rank = "Neo Miami Citizen";
									}
									createSoldier($conn, $nft_data->AssetName, $nft_data->name, $nft_data->summary, $rank, $armor_final, $gear_final, $level, $ipfs);
								}
							} // End if
						} // End foreach
					}// End if
					if($_SESSION['userData']['project_id'] == 2){
						if($counter >= 40){
							$ranks["Mayor"] = true;
						}else if($counter >= 30){
							$ranks["Don"] = true;
						}else if($counter >= 20){
							$ranks["Capo"] = true;
						}else if($counter >= 10){
							$ranks["VIP"] = true;
						}else if($counter >= 5){
							$ranks["Patron"] = true;
						}else if($counter >= 1){
							$ranks["Henchmen"] = true;
						}
					}else if($_SESSION['userData']['project_id'] == 4){
						$ranks["VIP"] = true;
					}
				} // End if
			} // End foreach
			updateSoldiers($conn, implode("', '", $asset_names));
			//getSoldiers($conn, 0);
		} // End if
		$_SESSION['userData']['rank'] = "";
		storeRank($ranks);
	}
// If address didn't change, load NFT data from the database to reduce loading times
}else if(isset($_SESSION['userData']['address']) && $address_changed == "false"){
	//echo "<div class='current-address'><strong>Current Address:</strong><br>".$_SESSION['userData']['address']."</div>";
	//getSoldiers($conn, 0);
	// Old session stuff
	/*
	foreach($_SESSION['userData']['nfts'] AS $index => $nft_data){
		$ipfs = substr($nft_data->image, 7, strlen($nft_data->image));
		//renderNFT($nft_data, $ipfs);
	}*/
}

if($_SESSION['userData']['project_id'] == 1){
	$currency = "SCRIP";
	$squad = "Super Soldier";
	$characters = "Troops";
}else if($_SESSION['userData']['project_id'] == 2){
	$currency = "DREAD";
	$squad = "Elite";
	$characters = "Henchmen";
}else if($_SESSION['userData']['project_id'] == 3){
	$currency = "TIDDIES";
	$squad = "VIP";
	$characters = "Perverts";
}else if($_SESSION['userData']['project_id'] == 4){
	$currency = "TIDDIES";
	$squad = "VIP";
	$characters = "Neo Miami Citizens";
}else{
	$currency = "SCRIP";
	$squad = "Super Soldier";	
}
?>
		<a name="dashboard" id="dashboard"></a>
		<!-- The flexible grid (content) -->
		<div class="row" id="row1">
		  <div class="main">
		    <div class="content">
				<!--<h1>Doc Oc & Ohh Meed's Drop Ship</h1>-->
			    <!-- Display results of Drop Ship run to user -->
				<div id="results">
					<?php if(isset($_SESSION['userData']['current_score']) && checkSquadCount($conn) < 1) { ?>
						<script>document.getElementById("results").style.backgroundImage = "url('<?php echo $prefix; ?>images/gameover.gif')";</script>
						<?php 
						// Send message to Discord channel with round death information only when there is an active score AND a notification hasn't already been sent.
						if(!isset($_SESSION['userData']['notification']) && isset($_SESSION['userData']['score'])) {
							$title = "Dead on Round ".$_SESSION['userData']['score'];
							ob_start(); // Start output buffering
							checkPlayerItems($conn);
							$list = ob_get_contents(); // Store buffer in variable
							ob_end_clean(); // End buffering and clean up
							$description = $_SESSION['userData']['name']." died during Round ".$_SESSION['userData']['score']."\n".evaluateText($list);
							$imageurl = "https://www.madballs.net".$prefix."images/die/".rand(1,3).".gif?var=123";
							discordmsg($title, $description, $imageurl); 
							$_SESSION['userData']['notification'] = "true";
						}
						?>
						<!--<img class="rounded" src='gameover.gif'/>-->
					<?php } else { ?>
						<?php if(isset($_POST['run'])) { ?>
							<script>document.getElementById("results").style.backgroundImage = "url('<?php echo $prefix; ?>images/reset/intro.jpg')";</script>
						<?php } else { ?>
							<script>document.getElementById("results").style.backgroundImage = "url('<?php echo $prefix; ?>images/dropship.jpg')";</script>
							<!--<img class="rounded" src='dropship.jpg'/>-->
						<?php } ?>
					<?php } ?>
				</div>
				
				<?php
				$audio2 = "loading";
				if($_SESSION["userData"]["project_id"] == 3){
					$audio2 = "splash";
				}else if($_SESSION["userData"]["project_id"] == 4){
					$audio2 = "laughing";
				}
				// Set results display variable
				$view_results = '
				<br>
				<button id="viewResults" class="button" onclick="displayRound('.$_SESSION["userData"]["project_id"].');" type="button">Next Round</button><br><br>
				<div id="disableMessage" style="display:none">false</div>
				<div id="resultsText"></div>
				<br>
				<audio id="audio1" controls onloadstart="this.volume=0.75" style="display:none">
				  <source id="audioSource1" src="sounds/static.mp3" type="audio/mpeg">
				Your browser does not support the audio element.
				</audio>
				<audio id="audio2" controls autoplay style="display:none">
				  <source id="audioSource2" src="sounds/'.$audio2.'.mp3" type="audio/mpeg">
				Your browser does not support the audio element.
				</audio>';
				?>
				
				
				<?php 
				if(!isset($_POST['instant_replay'])){
					// Display button to play Drop Ship
					if(!isset($_POST['run']) && !isset($_SESSION['userData']['current_score']) || (checkSquadCount($conn) >= 1 && isset($_SESSION['userData']['game_id'])) ) { ?>
						<br>
						<?php
						$battle = "";
						if(isset($_SESSION['userData']['battle_id'])){
							$battle = " Battle";
						}
						$play_button = '<form action="dashboard.php" method="post">
						  <input type="hidden" id="run" name="run" value="true">
						  <input class="button" type="submit" value="Play'.$battle.'">
						</form>';
						?>
						<?php if(checkSquadCount($conn) >= 1) { ?>
							<?php if($_SESSION['userData']['project_id'] == 3 && $patron == "true"){
								if($_SESSION["userData"]["discord_id"] == "772831523899965440" || $_SESSION["userData"]["discord_id"] == "897218513024974879"){ 
									echo $play_button;
								}else{
									echo "Cannot Play Until Fully Developed";
								}
							}if($_SESSION['userData']['project_id'] == 4 && $vip == "true"){
								echo '<form action="dashboard.php" method="post">
								  <input type="hidden" id="run" name="run" value="true">
								  <input class="button" type="submit" value="Play XXX Rated Game'.$battle.'">
								</form>';
							}else if($_SESSION['userData']['project_id'] == 3){ 
								echo "<p><strong>You Must Have 5+ NFTs to Play</strong></p>";
							}else if($_SESSION['userData']['project_id'] == 4){ 
									echo "<p><strong>You Must Have a VIP Token to Play</strong></p>";
							}else if($_SESSION['userData']['project_id'] != 3){ 
								echo $play_button;
 							} ?>
						<?php 
						} else {
							echo "<p><strong>Assemble Squad of ".$characters." to Play</strong></p>";
						}?>
					<?php
					// Display game status or Drop Ship Results
					} else if(!isset($_SESSION['userData']['current_score']) || checkSquadCount($conn) >= 1) { 
						if(!isset($_SESSION['userData']['game_id']) && !isset($_SESSION['userData']['battle_id'])) {
							?>
							<script>document.getElementById("results").style.backgroundImage = "url('<?php echo $prefix; ?>images/dropship.jpg')";</script>
							<script>
							var videoContent = "";
							videoContent ='<video id="dropshipPromoVideo" onloadstart="this.volume=0.15" autoplay controls><source src="videos/dropship.mp4" type="video/mp4">Your browser does not support the video tag.</video>';
							document.getElementById("results").innerHTML = videoContent;
							document.getElementById("dropshipPromoVideo").style.height = (document.getElementById("results").offsetHeight-10)+"px";
							</script>
							<?php
							echo "<h3>No active game.</h3>";
						} else { 
							// If battle id is present, unset it since the player is now viewing their battle results
							if(isset($_SESSION['userData']['battle_id'])){
								unset($_SESSION['userData']['battle_id']);
							}
							$hideLeaderboard = "true"; 
							// Unset notification flag
							unset($_SESSION['userData']['notification']);
							echo $view_results;
						} 
					} 
				}
				?>
				
				<?php if(isset($_POST['instant_replay'])) { 
					?><script>document.getElementById("results").style.backgroundImage = "url('<?php echo $prefix; ?>images/reset/intro.jpg')";</script><?php
					// Do not display instant replay button if play button is rendered
					echo $view_results;
					echo "<script type='text/javascript'>document.getElementById('disableMessage').innerHTML = 'true';</script>";
					$dropshipMarkup = getReplay($conn, $_POST['result_id']);
				}  
				if(!isset($_POST['instant_replay']) && isset($_SESSION['userData']['current_score']) && isset($_SESSION['userData']['game_id']) && !isset($_POST['deactivate']) && !isset($_POST['newgame'])){
					// Old instant replay by user id only, disabled until or if this can be figured out with result id.
					echo "<br>";
					instantReplayButton(checkMaxScoreResultID($conn, $_SESSION['userData']['user_id'], $_SESSION['userData']['current_score']), false);
				}
				?>
				
				<!-- Hidden Drop Ship results that are manipulated with Javascript -->
		        <div style="display:none;">
					<?php echo $dropshipMarkup; ?>
				</div>
		    </div>
		  </div>
		  <?php 
			// Load game id and current score that would typically happen when leaderboards loaded before that was moved to another page.
			checkGame($conn);
			if(isset($_SESSION['userData']['game_id'])){
				checkScore($conn);
			}
		  ?>

		  <div class="side">
			<?php $data = array();
				if(isset($_SESSION['userData']['user_id'])){
					$data = checkXP($conn, $_SESSION['userData']['user_id']);
					if(!empty($data)){
						$_SESSION['userData']['level'] = $data['level'];
					}
				}
			?>
			<h2><?php echo getProjectName($conn); ?></h2>
			<div class="content" id="player-stats">
				<ul>
					<div class="wallet-connect">
					<li class="role"><img class="icon" src="icons/wallet.png"/>
						<label for="wallets"><strong>Connect</strong>&nbsp;</label>
						<select onchange="javascript:connectWallet(this.options[this.selectedIndex].value);" name="wallets" id="wallets">
							<option value="none">Wallet</option>
						</select>
						<form id="addressForm" action="dashboard.php#barracks" method="post">
						  <input type="hidden" id="wallet" name="wallet" value="">	
						  <input type="hidden" id="address" name="address" value="">
						  <input type="submit" value="Submit" style="display:none;">
						</form>
					</li>
					</div>
					<?php
					$projects = getProjects($conn);
					?>
					<div class="wallet-connect select-project">
					<li class="role"><img class="icon" src="icons/cardano.png"/>
						<label for="projects"><strong>Select</strong>&nbsp;</label>
						<select onchange="javascript:selectProject(this.options[this.selectedIndex].value);" name="projects" id="projects">
							<option value="none">Project</option>
							<?php
								foreach($projects AS $id => $project){
									echo "<option value='".$id."'>".$project."</option>";
								}
							?>
						</select>
						<form id="projectForm" action="dashboard.php" method="post">
						  <input type="hidden" id="project_id" name="project_id" value="">
						  <input type="submit" value="Submit" style="display:none;">
						</form>
					</li>
					</div>
					<li class="role"><img class="icon" src="icons/level.png"/>Level <?php echo (!empty($data))?$data['level']:"0"; ?></li>
					<?php if($hideLeaderboard == "false") { 
						if(!empty($data)){
					?>
					<li class="role"><img class="icon" src="icons/xp.png"/><?php echo number_format($data['xp']); ?> / <?php echo number_format(ceil($data['ceiling'])); ?> XP</li>
					<?php 
						}
					} ?>
					<?php if($hideLeaderboard == "false") {
						if(!empty($data)){
					?>
					<li class="role">
					<img class="icon" src="icons/progress.png"/>
					<div class="w3-border">
					  <div class="w3-grey" style="width:<?php echo $data['percentage']; ?>%"></div>
					</div>
					</li>
					<?php 
						 }
					} ?>
					<li class="role"><img class="icon" src="icons/scoreboard.png"/>
						<?php echo "<strong>Games Played:</strong>&nbsp;".getScoreCount($conn); ?>
					</li>
					<?php if($hideLeaderboard == "false") { ?>					
					<li class="role"><img class="icon" src="icons/average.png"/>
						<?php echo "<strong>Average Score:</strong>&nbsp;".getAverageScore($conn); ?>
					</li>
					<?php } ?>
					<?php if($hideLeaderboard == "false") { ?>
					<li class="role"><img class="icon" src="icons/trophy.png"/>
						<?php echo "<strong>Top Score:</strong>&nbsp;".getTopScore($conn); ?>
					</li>
					<?php } ?>
					<?php
					if(isset($_SESSION["userData"]["rank"])){?>
						<li class="role"><img class="icon" src="icons/rank.png"/>
							<?php echo "<strong>".$_SESSION["userData"]["rank"]."</strong>";?>
						</li>
					<?php } ?>
				    <?php echo "<li class='role' id='super-soldier'><img class='icon' src='icons/supersoldier.png'/><strong>Super Soldier</strong></li>"; ?>
					<?php $scrip=number_format(checkBalance($conn)); ?>
					<li class="role"><img class="icon" src="icons/scrip.png"/>
						<?php echo $scrip; ?> <?php echo "$".$currency ?>
					</li>
				</ul>
			</div>
		  </div>
		
		</div>
    	
		<?php if($hideLeaderboard == "false") { ?>
		<div class="row">
			<div class="soldiers" id="soldiers-panel">
			<?php if(checkSquadCount($conn) > 0){?>
			    <div class="content" id="squad-panel">
				<h2 id="squad">Squad</h2>
					<div id="nfts" class="nfts">
						<?php
						getSoldiers($conn, 1); 
						?>
					</div>
				</div>
			<?php } ?>
				<a name="barracks" id="barracks"></a>
				<div class="content" id="barracks-panel">
				<h2><?php echo evaluateText("Barracks"); ?></h2>
					<?php filterTroops("dashboard"); ?>
					<div id="nfts" class="nfts">
						<?php 
						getSoldiers($conn, 0, $filterby); 
						?>
					</div>
				</div>
			</div>
			<a name="armory" id="armory"></a>
			<div class="col1of3">
				<h2><?php echo evaluateText("Armory"); ?></h2>
 				 <div id="armory-icons">
					  <img onclick="javascript:toggleArmory(document.getElementById('inventory'), this)" class="armory-icon" id="inventory-icon" src="icons/inventory.png"/>
					  <img onclick="javascript:toggleArmory(document.getElementById('weapons'), this)" class="armory-icon icon-disabled" id="weapon-icon" src="icons/weapons.png"/>
					  <img onclick="javascript:toggleArmory(document.getElementById('armor'), this)" class="armory-icon icon-disabled" id="armor-icon" src="icons/shield.png"/>
					  <img onclick="javascript:toggleArmory(document.getElementById('equipment'), this)" class="armory-icon icon-disabled" id="equipment-icon" src="icons/equipment.png"/>
				</div>
				 <div class="content" id="inventory">
					<ul><li class="role"><img class="icon" src="icons/inventory.png"/><h3>Inventory</h3></li>
						<li class="role"><img class="icon" src="icons/scrip.png"/>
						<?php echo $scrip; ?> <?php echo "$".$currency ?>
						</li>
					</ul>
					<?php checkInventory($conn);?>
				 </div>
				 <div class="content" id="weapons" style="display:none">
					<ul><li class="role"><img class="icon" src="icons/weapons.png"/><h3>Weapons</h3></li>
						<li class="role"><img class="icon" src="icons/scrip.png"/>
						<?php echo $scrip; ?> <?php echo "$".$currency ?>
						</li>
					</ul>
					<?php loadItems($conn, "Weapon", $heavy, $medium, $light, $base, $melee, $demolition, $extralife, $scrip);?>
				 </div>
				 <div class="content" id="armor" style="display:none">
					<ul><li class="role"><img class="icon" src="icons/shield.png"/><h3>Armor</h3></li>
						<li class="role"><img class="icon" src="icons/scrip.png"/>
						<?php echo $scrip; ?> <?php echo "$".$currency ?>
						</li>
					</ul>
					<?php loadItems($conn, "Armor", $heavy, $medium, $light, $base, $melee, $demolition, $extralife, $scrip);?>
				 </div>
				 <div class="content" id="equipment" style="display:none">
					<ul><li class="role"><img class="icon" src="icons/equipment.png"/><h3>Equipment</h3></li>
						<li class="role"><img class="icon" src="icons/scrip.png"/>
						<?php echo $scrip; ?> <?php echo "$".$currency ?>
						</li>
					</ul>
					<?php loadItems($conn, "Equipment", $heavy, $medium, $light, $base, $melee, $demolition, $extralife, $scrip);
					?>
				 </div>
			</div>
		</div>
		<?php } ?>
		<!-- Footer -->
		<div class="footer">
		  <p>Drop Ship | Ohh Meed's Shorty Verse<br>Copyright © <span id="year"></span>
			<!-- Admin functions to deactivate current game or create a new game -->
			<?php if($_SESSION['userData']['discord_id'] == "772831523899965440") {
				if(isset($_SESSION['userData']['game_id'])) { ?>
					<form action="dashboard.php" method="post">
					  <input type="hidden" id="deactivate" name="deactivate" value="true">
					  <input class="button" type="submit" value="Deactivate Game">
					</form>
					<br>
				<?php 
				} else { ?>
					<form action="dashboard.php" method="post">
					  <p>Name: </p><input id="name" name="name" value="">
  					  <p>Number of Prizes: </p><input id="prizes" name="prizes" value=""><br><br>
					  <input type="hidden" id="newgame" name="newgame" value="true">
					  <input class="button" type="submit" value="New Game">
					</form>
					<br>
				<?php }
			 } ?>
		</div>
	</div>
  </div>
</body>
<?php
// Close DB Connection
$conn->close();
if($heavy == "true" && $medium == "true" && $light == "true" && $base == "true" && $extralife == "true" && $demolition == "true" && $melee == "true") { ?>
	<script type="text/javascript">
	document.getElementById('super-soldier').style.display = "flex";
	document.getElementById('squad').innerHTML = '<?php echo $squad; ?> Squad';
	</script>
<?php } ?>
<?php
if(isset($_SESSION["userData"]["wallet"])){
	echo "<script type='text/javascript'>function updateWallet(){document.getElementById('wallets').value = '".$_SESSION["userData"]["wallet"]."';}</script>";
}
if($filterby != ""){
	echo "<script type='text/javascript'>document.getElementById('filterTroops').value = '".$filterby."';</script>";
}
if(isset($_SESSION['userData']['project_id'])){
	echo "<script type='text/javascript'>document.getElementById('projects').value = '".$_SESSION['userData']['project_id']."';</script>";
}
if($_SESSION['userData']['project_id'] == 3){?>
	<script type='text/javascript'>document.body.style.backgroundImage = "url('/filthy-mermaid/filthymermaidbackground.jpg')";</script>
<?php }
if($_SESSION['userData']['project_id'] == 4){?>
	<script type='text/javascript'>document.body.style.backgroundImage = "url('/oculus-lounge/oculusloungebackground.png')";</script>
<?php }
?>
<script type="module" src="wallet.js?var=<?php echo rand(0,999); ?>"></script>
<script type="text/javascript" src="dropship.js?var=<?php echo rand(0,999); ?>"></script>
</html>