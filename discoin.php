<?php
include 'db.php';
include 'webhooks.php';
include 'dropship.php';
include 'header.php';

if(!isset($_SESSION['userData']['transaction'])){
	$six_digit_random_number = random_int(200000, 999999);
	$_SESSION['userData']['transaction'] = $six_digit_random_number;
}

$discoin_policy_id = "5612bee388219c1b76fd527ed0fa5aa1d28652838bcab4ee4ee63197";


	$ch = curl_init("https://api.koios.rest/api/v0/address_txs");
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, '{"_addresses":["addr1qykk9ue0wmnky9mh453ln84tf472036wqmhj46a45m6a8xqpqyck03v2n0nhz94r39gymw6q9xa0d8pg6daf3rsz7y3qdy8m9t"],"_after_block_height":6238675}');
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec( $ch );
	// If you need to debug, or find out why you can't send message uncomment line below, and execute script.
	$response = json_decode($response);
	//print_r($response[0]->asset_list);
	//exit;
	curl_close( $ch );

	$transaction_hash = $response[0]->tx_hash;

	$ch = curl_init("https://api.koios.rest/api/v0/tx_info");
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, '{"_tx_hashes":["'.$transaction_hash.'"]}');
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec( $ch );
	// If you need to debug, or find out why you can't send message uncomment line below, and execute script.
	$response = json_decode($response);
	//print_r($response[0]->asset_list);
	//exit;
	curl_close( $ch );
	
	$count = count($response[0]->outputs);
	$ada = $response[0]->outputs[$count]->value;
	$quantity = $response[0]->outputs[$count]->asset_list[0]->quantity;
	$policy_id = $response[0]->outputs[$count]->asset_list[0]->policy_id;

	echo "ADA: ".$ada."<br>";
	echo "Qty: ".$quantity."<br>";
	echo "PID: ".$policy_id."<br>";
	
	print_r($response);

	$_SESSION['userData']['transaction'] = "187014";
	if(str_contains($ada, $_SESSION['userData']['transaction'])){
		//$discoin_policy_id = "d0112837f8f856b2ca14f69b375bc394e73d146fdadcc993bb993779";
		if($policy_id == $discoin_policy_id){
			if($quantity == 100000000000){ 
				$flag = true;
				echo "true";
			}
		}
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
	//checkTransaction();
</script>
</html>
