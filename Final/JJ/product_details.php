<?php
	require './includes/header.php';
	require_once '../../mysqli_connect.php';
	
	echo '<main>';
	
	function shortTitle ($title){
		$title = substr($title, 0, -4);
		$title = str_replace('_', ' ', $title);
		$title = ucwords($title);
		return $title;
	}
	if(isset($_GET['image_id'])) {
		$imgID = filter_var($_GET['image_id'], FILTER_VALIDATE_INT);
		$getDetails= "SELECT * FROM JJ_images WHERE image_id = ?";
		$stmt = mysqli_prepare($dbc, $getDetails);
		mysqli_stmt_bind_param($stmt, 'i', $imgID);
		mysqli_stmt_execute($stmt);
		$result=mysqli_stmt_get_result($stmt);
		$rows = mysqli_num_rows($result);
		
		if ($rows == 1) { // Valid print ID.
			// Fetch the information.
			$item = mysqli_fetch_assoc($result);
			// Retrieve the query results into scalar variables
			$price = $item['price'];
			
			echo "<h2> Purchase ".(shortTitle($item['filename'])). ":</h2>"; 					
			echo '<img src =  "images/'. $item['filename'].  '">';
			echo "<h3><strong>Description:</strong></h3>";
			echo "<h4>".$item["caption"]."</h4>";
			echo "<h4>".$item['details']."</h4>";
			
			echo '<form style ="display:inline:" action = "cart.php" method = "post">';
			echo "<p><h4><strong>Price: </strong>$".$price. " ";
			
				echo '<input type = "hidden" name = "action" value = "add">';
				echo '<input type = "hidden" name = "image_id" value = "'.$imgID.'">';
				echo '<input type = "hidden" name = "qty" value = 1>';
				echo '<input type = "submit" value = "Add to Cart">';
				echo "</h4></p></form>";
			If (!empty($_SESSION['cart'])) {
			echo 'or <a href="cart_view.php"><button>View Cart</button></a>';
			}
		}
		else {
			echo "<main><h2>We are unable to process your request at  this  time.</h2><h3>Please try again later.</h3></main>";
			include 'includes/footer.php';
			exit;
		}
	}else {
		echo "<main><h2>You have reached this page in error</h2><h3>Use the menu at the left to view our products.</h3></main>";
		include 'includes/footer.php'; 
		exit;
	   } 
echo '</main>';
include 'includes/footer.php'; ?>