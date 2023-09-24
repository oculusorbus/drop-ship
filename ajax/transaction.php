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

	//$transaction_hash = $response[0]->tx_hash;

	//foreach($response AS $index => $value){
	for ($x = 0; $x <= count($response); $x++) {
		$ch = curl_init("https://api.koios.rest/api/v0/tx_info");
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, '{"_tx_hashes":["'.$response[$x]->tx_hash.'"]}');
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec( $ch );
		// If you need to debug, or find out why you can't send message uncomment line below, and execute script.
		$response = json_decode($response);
		//print_r($response[0]->asset_list);
		//exit;
		curl_close( $ch );

		$count = count($response[0]->outputs)-1;
		$ada = "";
		if(isset($response[0]->outputs[$count]->value)){
			$ada = $response[0]->outputs[$count]->value;
		}
		$quantity = "";
		if(isset($response[0]->outputs[$count]->asset_list[0]->quantity)){
			$quantity = $response[0]->outputs[$count]->asset_list[0]->quantity;
		}
		$policy_id = "";
		if(isset($response[0]->outputs[$count]->asset_list[0]->policy_id)){
			$policy_id = $response[0]->outputs[$count]->asset_list[0]->policy_id;
		}

		//echo "ADA: ".$ada."<br>";
		//echo "Qty: ".$quantity."<br>";
		//echo "PID: ".$policy_id."<br>";

		$_SESSION['userData']['transaction'] = "187084";
		if(str_contains($ada, $_SESSION['userData']['transaction'])){
			if($policy_id == $discoin_policy_id){
				if($quantity == 100000000000){ 
					$flag = true;
					echo "true";
				}
			}
		}
		if($index >= 1){
			break;
		}
	}
	//sleep for 5 seconds
	sleep(5);
}
?>