<?php
include 'db.php';
include 'webhooks.php';
include 'dropship.php';
include 'header.php';
?>
		<div class="row" id="row3">
			<div class="main">
				<h2>Achievements</h2>
			    <div class="content">
					<div id="achievements" class="achievements">
						<?php loadAchievements($conn, $heavy, $medium, $light, $base, $melee, $demolition, $extralife); ?>
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
<script type="text/javascript">
// Reorganize achivements with unlocked at top, locked at bottom
var unlocked = "";
var locked = "";
[].forEach.call(document.querySelectorAll('.unlocked'), function (el) {
  unlocked += el.outerHTML;
});
[].forEach.call(document.querySelectorAll('.locked'), function (el) {
  locked += el.outerHTML;
});
var achievements = document.getElementById('achievements');
achievements.innerHTML = "";
achievements.innerHTML = unlocked;
achievements.innerHTML += locked;
</script>
</html>