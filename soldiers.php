<?php
include 'db.php';
include 'webhooks.php';
include 'dropship.php';
include 'header.php';
?>
		<div class="row" id="row3">
			<div class="main">
				<h2>Soldiers</h2>
			    <div class="content">
					<div id="soldiers" class="soldiers-panel">
						<?php getSoldiers($conn); ?>
					</div>
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