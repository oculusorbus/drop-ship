<?php
include 'db.php';
include 'webhooks.php';
include 'dropship.php';
include 'header.php';
?>
		<div class="row" id="row4">
			<div class="main">
				<h2>Transaction History</h2>
					<div class="content" id="transactions-pane">
					<?php if($hideLeaderboard == "false") {
						transactionHistory($conn);
						echo "</table>";
					}?>
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
// Close DB Connection
$conn->close();
?>
<script type="text/javascript" src="dropship.js"></script>
</html>