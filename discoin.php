<?php
include 'db.php';
include 'webhooks.php';
include 'dropship.php';
include 'header.php';

if(!isset($_SESSION['userData']['transaction'])){
	$six_digit_random_number = random_int(100000, 999999);
	$_SESSION['userData']['transaction'] = $six_digit_random_number;
}
?>
		<div class="row" id="row4">
			<div class="main">
				<h2>DISCOIN</h2>
				<div class="content">
					<p>
					Please send 1.<?php echo $_SESSION['userData']['transaction']; ?> ADA and 1,000 DISCOIN to the following address: addr1qykk9ue0wmnky9mh453ln84tf472036wqmhj46a45m6a8xqpqyck03v2n0nhz94r39gymw6q9xa0d8pg6daf3rsz7y3qdy8m9t
					</p>
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
<script type="text/javascript">
	checkTransaction();
</script>
</html>
