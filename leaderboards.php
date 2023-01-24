<?php
include 'db.php';
include 'webhooks.php';
include 'dropship.php';
include 'header.php';
?>

		<?php if($hideLeaderboard == "false") { ?>
		<a name="leaderboards" id="leaderboards"></a>
		<div class="row" id="row3">
			<div class="col1of3">
			    <div class="content">
					<?php if(isset($_SESSION['userData']['game_id']) && $hideLeaderboard == "false") {
						echo "<h2>Current Game</h2>";
						checkLeaderboard($conn, false);
					} else {
						echo "<h2>No Active Game</h2>";
					}?>
				</div>
			</div>
			<div class="col1of3">
			    <div class="content">
					<?php if($hideLeaderboard == "false") {
						echo "<h2>All Time High</h2>";
						checkATHLeaderboard($conn, false);
					}?>
				</div>
			</div>
			<div class="col1of3">
				<div class="content">
					<?php if($hideLeaderboard == "false") {
						echo "<h2>Levels / XP</h2>";
						checkXPLeaderboard($conn, false);
					}?>
				</div>
			</div>
		</div>
		<?php } ?>
		<!-- Footer -->
		<div class="footer">
		  <p>Drop Ship | Ohh Meed's Shorty Verse<br>Copyright © <span id="year"></span>
		</div>
	</div>
  </div>
</body>
<?php
// Close DB Connection
$conn->close();
?>
<script type="text/javascript" src="dropship.js"></script>
</html>