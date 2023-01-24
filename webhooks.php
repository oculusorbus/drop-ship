<?PHP
include('/credentials/webhooks_credentials.php');
//
//-- https://gist.github.com/Mo45/cb0813cb8a6ebcd6524f6a36d4f8862c
//
    function discordmsg($title, $description, $imageurl, $project_id=0) {
		global $webhook;
		// Delay execution by 1 minute to allow the player to finish their game before sending results to Discord

	    $timestamp = date("c", strtotime("now"));
	    $msg = json_encode([
	    // Message
	    //"content" => "",

	    // Username
	    "username" => "Kill Bot",

	    // Avatar URL.
	    // Uncomment to use custom avatar instead of bot's pic
	    "avatar_url" => "https://cdn.discordapp.com/app-icons/983993436694794261/8c3b958cac5369b56486c326d8c3e5d1.png?size=256",

	    // text-to-speech
	    "tts" => false,

	    // file_upload
	    // "file" => "",

	    // Embeds Array
	    "embeds" => [
		        [
		            // Title
		            "title" => $title,

		            // Embed Type, do not change.
		            "type" => "rich",

		            // Description
		            "description" => $description,

		            // Link in title
		            "url" => "https://madballs.net/drop-ship/index.php",

		            // Timestamp, only ISO8601
		            "timestamp" => $timestamp,

		            // Left border color, in HEX
		            "color" => hexdec( "000000" ),

		            // Footer text
					/*
		            "footer" => [
		                "text" => "Drop Ship",
		                "icon_url" => "https://www.madballs.net/dropship/vip.gif"
		            ],*/

		            // Embed image
		            "image" => [
		                "url" => $imageurl
		            ],

		            // thumbnail
		            "thumbnail" => [
		                "url" => "https://www.madballs.net/dropship/vip.gif"
		            ],

		            // Author name & url
					/*
		            "author" => [
		                "name" => "Kill Bot",
		                "url" => "https://www.madballs.net/dropship"
		            ],*/

		            // Custom fields
					/*
		            "fields" => [
		                // Field 1
		                [
		                    "name" => "Field #1",
		                    "value" => "Value #1",
		                    "inline" => false
		                ],
		                // Field 2
		                [
		                    "name" => "Field #2",
		                    "value" => "Value #2",
		                    "inline" => true
		                ]
		                // etc
		            ]*/
		        ]
		    ]
		], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
        if($webhook != "") {
            $ch = curl_init( $webhook );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $msg);
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt( $ch, CURLOPT_HEADER, 0);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
 
            $response = curl_exec( $ch );
            // If you need to debug, or find out why you can't send message uncomment line below, and execute script.
            echo $response;
            curl_close( $ch );
        }
    }
 
//    discordmsg($msg, $webhook); // SENDS MESSAGE TO DISCORD
//    echo "sent?";
?>