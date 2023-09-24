<?php
session_start();

$discoin_policy_id = "5612bee388219c1b76fd527ed0fa5aa1d28652838bcab4ee4ee63197";

$flag = false;
while(!$flag) {
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

	$ada = $response[0]->inputs[1]->value;
	$quantity = $response[0]->inputs[1]->asset_list[0]->quantity;
	$policy_id = $response[0]->inputs[1]->asset_list[0]->policy_id;

	//echo "ADA: ".$ada."<br>";
	//echo "Qty: ".$quantity."<br>";
	//echo "PID: ".$policy_id."<br>";

	$_SESSION['userData']['transaction'] = "189560";
	if(str_contains($ada, $_SESSION['userData']['transaction'])){
		$discoin_policy_id = "d0112837f8f856b2ca14f69b375bc394e73d146fdadcc993bb993779";
		if($policy_id == $discoin_policy_id){
			if($quantity == 1){
				$flag = true;
				echo "true;"
			}
		}
	}
	//sleep for 5 seconds
	sleep(5);
}
?>