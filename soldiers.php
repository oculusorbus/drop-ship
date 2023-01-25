<?php
include 'db.php';
include 'webhooks.php';
include 'dropship.php';
include 'header.php';
?>
		<div class="row" id="row3">
			<div class="main">
				<h2><?php echo evaluateText("Registered Soldiers");?></h2>
			    <div class="content">
					<div id="soldiers" class="soldiers-panel">
						<?php filterTroops("soldiers"); ?>
						<div class="nfts">
						<?php getSoldiers($conn, null, $filterby, true); ?>
						</div>
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
if($filterby != ""){
	echo "<script type='text/javascript'>document.getElementById('filterTroops').value = '".$filterby."';</script>";
}
// Close DB Connection
$conn->close();
?>
<script type="text/javascript" src="dropship.js"></script>
</html>