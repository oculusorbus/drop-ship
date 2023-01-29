<?php
include 'db.php';
include 'webhooks.php';
include 'dropship.php';
include 'header.php';

// Handle wager form submission
if(isset($_POST['wager'])) {
	if($_POST['wager'] >= 1 && is_numeric($_POST['wager'])){
		if($_POST['wager'] <= checkBalance($conn)){
			createBattle($conn, $_POST['wager']);
		}else{
			echo "<script type='text/javascript'>alert('You do not have enough funds to place this wager and create a battle.');</script>";
		}
	}else{
		echo "<script type='text/javascript'>alert('Please enter a valid wager amount.');</script>";
	}
}
?>
		<div class="row">
			<div class="main">
				<h2><?php echo evaluateText("PvP Battles");?></h2>
			</div>
		</div>
		<div class="row">
			<div class="side">
				<div class="content" id="wagers">
					<p><strong>Current Balance: </strong><?php echo number_format(checkBalance($conn))." $".evaluateText("SCRIP"); ?></p>
					<form id="wagerForm" action="battles.php" method="post">
					  <label for="wager">Enter <?php echo "$".evaluateText("SCRIP");?> Wager:</label>
					  <input id="wager" name="wager" value=""><br><br>
					  <input class="button" type="submit" value="Create Battle">
					</form>
				</div>
			</div>
			<div class="main">
				<div class="content" id="battles">
					<ul class='roles'>
					 <li class='role'>
						<img class="icon" src="icons/dropship.png">
					<h3>Available Battles</h3>
					 </li>
					</ul>
					<?php getBattles($conn); ?>
				</div>
			</div>
		</div>

		<!-- Footer -->
		<div class="footer">
		  <p>Drop Ship | Ohh Meed's Shorty Verse<br>Copyright © <span id="year"></span>
		</div>
	</div>
  </div>
</body>
<?php
if($filterby != ""){
	echo "<script type='text/javascript'>document.getElementById('filterTroops').value = '".$filterby."';</script>";
}
// Close DB Connection
$conn->close();
?>
<script type="text/javascript" src="dropship.js"></script>
</html>
