<?php 

session_start();
require 'includes/header.php';
?>
	<main>
	<?php if (isset($_SESSION['fn']))  {			
			$_SESSION=array();
			session_destroy();
			setcookie('PHPSESSID','',time()-3600,'/');			
			
			$message2 = "You are now logged out";
		} else { 
			
			$message2 = 'Please use the menu at the left';	
		}
		// Print the message:
		
		
	
	
		echo '<h3>'.$message2.'</h3>';
		?>
	</main>
	<?php // Include the footer and quit the script:
	include ('./includes/footer.php'); 
	?>
	