<?php
include 'db.php';
include 'webhooks.php';
include 'dropship.php';
include 'header.php';

if(!isset($_SESSION['userData']['transaction'])){
	$six_digit_random_number = random_int(200000, 999999);
	$_SESSION['userData']['transaction'] = $six_digit_random_number;
}
?>
		<div class="row" id="row4">
			<div class="main">
				<h2>Buy Temporary VIP Pass with DISCOIN</h2>
				<div class="content">
					<p>
					Please send 1.<?php echo $_SESSION['userData']['transaction']; ?> ADA and 1,000 DISCOIN in a single transaction to the following address:
					</p>
					<p>
					addr1qykk9ue0wmnky9mh453ln84tf472036wqmhj46a45m6a8xqpqyck03v2n0nhz94r39gymw6q9xa0d8pg6daf3rsz7y3qdy8m9t
					</p>
					<p>
					After successfully sending the transaction, please click this button to verify that it has been received with the correct amount.
					</p>
					<button type="button" onclick="checkTransaction();">Verify Transaction</button>
					<p>
					Upon successful receipt of the transaction, you will receive a confirmation message that you've received a temporary VIP role in Oculus Lounge discord.
					</p>
					<p>
					If a significant amount of time passes with no confirmation, feel free to refresh this webpage and verify again. You do not need to send another transaction to verify.
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
</html>
