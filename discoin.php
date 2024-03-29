<?php
include 'db.php';
include 'webhooks.php';
include 'role.php';
include 'dropship.php';
include 'header.php';

if(!isset($_SESSION['userData']['transaction'])){
	$six_digit_random_number = random_int(200000, 999999);
	$_SESSION['userData']['transaction'] = $six_digit_random_number;
}

?>
		<div class="row" id="row4">
			<div class="main">
				<h2>Buy Temporary VIP Access with DISCOIN</h2>
				<div class="content">
					<p>
					If you don't hold a VIP token, you can purchase temporary VIP access with DISCOIN. You will receive temporary VIP roles in Discord and temporary access to the Oculus Lounge game.<br>
					Be warned that the Oculus Lounge bouncers may kick you out in a few minutes or a few hours for sneaking into the VIP lounge. Even if you're kicked out quickly, your session for the game will last as long as you are logged in.
					</p>
					<p>
					Please send 1.<?php echo $_SESSION['userData']['transaction']; ?> ADA and 1,000 DISCOIN in a single transaction to the following address:
					</p>
					<p>
					$tiddies</p>
					<p>OR</p>
					<p>addr1q9spjm8huu3svyh286wcrfs8hvv2pa0rlewk5zsj308wwduf9vr444v7z8xktt4l5z20f6dv2yujs9z6gc3hxzqjunqsrl06ny</p>
					</p>
					<p>
					After successfully sending the transaction, please click this button to verify that it has been received with the correct amount.
					</p>
					<button class="button" type="button" onclick="checkTransaction();">Verify Transaction</button>
					<p style="background-color:black">
					<img id="loading-transaction" src="../oculus-lounge/images/loading.gif" style="display:none;margin-left: auto;margin-right: auto;"/>
					</p>
					<p>
					Upon successful verification of the transaction, you will receive a confirmation message detailing your temporary VIP access for Discord and the Oculus Lounge game.
					</p>
					<p>
					If several minutes pass with no confirmation, feel free to refresh this webpage and verify again. You do not need to send another transaction to verify after refreshing.
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
