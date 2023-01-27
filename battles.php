<?php
include 'db.php';
include 'webhooks.php';
include 'dropship.php';
include 'header.php';
?>
		<div class="row" id="row3">
			<div class="main">
				<h2><?php echo evaluateText("PvP Battles");?></h2>
			    <div class="content" id="battles">
					<form id="wagerForm" action="dashboard.php" method="post">
					  <label for="wager">Enter <?php echo "$".evaluateText("SCRIP");?> Wager:</label>
					  <input id="wager" name="wager" value=""><br><br>
					  <input class="button" type="submit" value="Create PvP Battle">
					</form>
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
