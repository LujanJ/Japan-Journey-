<?php //The cart workings
	session_start();
	// Create a cart array if needed
	if (empty($_SESSION['cart'])) { 
		$_SESSION['cart'] = array(); 
	}
	// Determine the action to perform

	if (isset($_GET['action'])) { //sent via query string
		$action = filter_var($_GET['action'], FILTER_SANITIZE_STRING);
	}
	if (isset($_POST['action'])) { //sent from product_details.php
		$action = filter_var($_POST['action'], FILTER_SANITIZE_STRING);
	}
    if ($action === NULL) { //neither
        $action = 'show_add_item';
    }
	echo "$action";
	// Add or update cart as needed
	switch($action) {
		case 'details':
			include('product_details.php');
			break;
		case 'add':
			$imgID = filter_var($_POST['image_id'], FILTER_VALIDATE_INT );
			$qty = filter_var($_POST['qty'], FILTER_VALIDATE_INT);
			if (isset($_SESSION['cart'][$imgID])) { //item already in cart
				$_SESSION['cart'][$imgID]['quantity'] += $qty; //update the quantity
				//Get the price from the cart in $_SESSION and set it to $cart_price
				
			} else { // New product to the cart.
				// Get the print's data from the database:
				require_once '../../../mysqli_connect.php'; // Connect to the database.
				$getImage= "SELECT * FROM JJ_images WHERE image_id = ?";
				$stmt=mysqli_prepare($dbc, $getImage);	
				mysqli_stmt_bind_param($stmt, i, $imgID);
				mysqli_stmt_execute($stmt);
				$result=mysqli_stmt_get_result($stmt);
				$rows = mysqli_num_rows($result);
				if ($rows == 1) { // Valid print ID.
					// Fetch the information.
					$item = mysqli_fetch_assoc($result);
					$imgID = $item['image_id'];
					$imgTitle = $item['caption'];
					$imgPrice = $item['price'];
					//Compare
					
					// Add to the cart:
					$_SESSION['cart'][$imgID] = array ('caption'=> $imgTitle, 'quantity' => 1, 'price' => $imgPrice);
				} else { // Not a valid print ID.
					require 'includes/header.php';
					echo '<main><h2>We are unable to process your request at  this  time.</h2><h3>Please try again later.</h3></main>';
					include 'includes/footer';
					exit;
				}	
			} // end of new product else
			include('cart_view.php');
			break;
		case 'update':
			$new_qty_list = filter_var($_POST['newqty'], FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
			foreach($new_qty_list as $img => $qty) {
				if ($_SESSION['cart'][$img]['quantity'] != $qty) {
					 $quantity = (int) $qty;
					if (isset($_SESSION['cart'][$img])) {
						if ($quantity <= 0) {
							unset($_SESSION['cart'][$img]);
						} else {
							$_SESSION['cart'][$img]['quantity'] = $quantity;
						}
					}
				}
			}
			include('cart_view.php');
			break;
		case 'show_cart':
			include('cart_view.php');
			break;
		case 'empty_cart':
			unset($_SESSION['cart']);
			include('cart_view.php');
			break;
	} //end switch
?>
