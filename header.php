<!doctype html>
<html>
<head>
  <title>Drop Ship</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <!--<link href="dist/output.css" rel="stylesheet">-->
  <link href="dist/flexbox.css?var=<?php echo rand(0,999); ?>" rel="stylesheet">
  <?php
  if($_SESSION["userData"]["project_id"] == 4){?>
	<?php if(str_contains($_SERVER['PHP_SELF'], "battles.php")){?>
	<style>
		.button{
			filter: hue-rotate(145deg);
		}
	</style>
	  <?php }else{ ?>
		<style>
			.button, .small-button{
				filter: hue-rotate(145deg);
			}
			.credit{
				filter: hue-rotate(45deg);
			}
			.battle-credit{
				filter: hue-rotate(145deg);
			}
		</style>
	  <?php } ?>
  <?php } ?>
</head>
<body>
	<div id="loading" <?php echo (isset($_POST['run']) || isset($_POST['instant_replay']))?'style="display:none"':""; ?>>
	  <img id="loading-image" src="<?php echo $prefix;?>images/loading.gif" alt="Loading..." />
	</div>
	<div class="container">
		<!-- Navigation Bar -->
		<div class="navbar">
	      <img class="rounded-full" src="<?php echo $avatar_url?>" />
		  <a href="https://discord.gg/DHbGU9ZDyG"><?php echo $name;?></a>
		  <a class="navbar-first" href="dashboard.php">Dashboard</a>
    	  <a href="dashboard.php#barracks"><?php echo evaluateText("Barracks");?></a>
    	  <a href="dashboard.php#armory"><?php echo evaluateText("Armory");?></a>
    	  <a href="battles.php"><?php echo evaluateText("Battles");?></a>
    	  <a href="soldiers.php"><?php echo evaluateText("Soldiers");?></a>
    	  <a href="leaderboards.php">Leaderboards</a>
    	  <a href="achievements.php">Achievements</a>
    	  <a href="transactions.php">Transactions</a>
		  <a href="logout.php">Logout</a>
		</div>
		<button onclick="topFunction()" id="back-to-top-button" title="Go to top">^</button>
