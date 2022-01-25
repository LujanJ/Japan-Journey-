	<footer>
		<p>&copy;
		<?php
			date_default_timezone_set("America/New_York");
			$startYear = 2020;
			$thisYear = date('Y');
			if ($startYear == $thisYear) {
				echo $startYear;
			} else {
				echo "$startYear".'&ndash;'."$thisYear";
			}
		?>
		Jaime Lujan</p>
	</footer>
</div>
</body>
</html>

