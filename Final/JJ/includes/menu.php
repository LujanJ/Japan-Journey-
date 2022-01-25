	<?php 
	$currentPage = basename($_SERVER['SCRIPT_FILENAME']); ?>
	<ul id="nav">
        <li><a href="index.php" <?php if ($currentPage == 'index.php') {echo 'id="here"'; } ?>>Home</a></li>
        <li><a href="gallery.php" <?php if ($currentPage == 'gallery.php') {echo 'id="here"'; } ?>>Gallery</a></li>
		<li><a href="product_list.php" <?php if ($currentPage == 'product_list.php') {echo 'id="here"'; } ?>>Purchase Prints</a></li>
        <li><a href="contact_us.php" <?php if ($currentPage == 'contact_us.php') {echo 'id="here"'; } ?>>Contact</a></li>
		
		<?php if (isset($_SESSION['email']))  { ?>
		<li><a href="logged_out.php" <?php if ($currentPage == 'login.php') {echo 'id="here"'; } ?>>Log Out</a></li>
		<?php } 
		
		else {?>
		<li><a href="create_acct.php" <?php if ($currentPage == 'create_acct.php') {echo 'id="here"'; } ?>>Register</a></li> 
		<li><a href="login.php" <?php if ($currentPage == 'login.php') {echo 'id="here"'; } ?>>Login</a></li> 
		<?php } ?>
	</ul>
	