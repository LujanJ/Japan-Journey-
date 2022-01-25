<?php //This is the login page for registered users
require 'includes/header.php';
if (isset($_POST['send'])) {
	$errors = array();
	
	$valid_email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);	//returns a string or null if empty or false if not valid	
	if (trim($_POST['email']==''))
		$errors[] = 'missing_email';
	elseif (!$valid_email)
		$errors[] = 'invalid_email';
	else 
		$email = $valid_email;
	
	$password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
	if (empty($password))
		$errors[]='missing_password';

	while (!$errors){ 
		require_once ('../../../mysqli_connect.php'); // Connect to the db.
		// Make the query:
		$sql = "SELECT firstName, emailAddr, pw FROM JJ_reg_users WHERE emailAddr = ?";
		$stmt = mysqli_prepare($dbc, $sql);
		mysqli_stmt_bind_param($stmt, 's', $email);
		mysqli_stmt_execute($stmt);
		$result=mysqli_stmt_get_result($stmt);
		$rows = mysqli_num_rows($result);
		mysqli_free_result($stmt);
		if ($rows==0) 
			$errors[] = 'no_email';
		else { // email found, validate password
			$result=mysqli_fetch_assoc($result); //convert the result object pointer to an associative array 
			$pw_hash=$result['pw'];
			if (password_verify($password, $pw_hash )) { //passwords match
				$firstName = $result['firstName'];
				//your code here
				
				
				exit;
			}
			else {
				$errors[]='password';
			}
		} 
	   } // end while 	
} //end isset $_POST['send']
?>
	<main>
        <h2>Japan Journey</h2>
        <p>Ut enim ad minim veniam, quis nostrud exercitation consectetur adipisicing elit. Velit esse cillum dolore ullamco laboris nisi in reprehenderit in voluptate. Mollit anim id est laborum. Sunt in culpa duis aute irure dolor excepteur sint occaecat.</p>
        <form method="post" action="login.php">
			<fieldset>
				<legend>Registered Users Login</legend>
				<?php if ($errors) { ?>
				<p class="warning">Please fix the item(s) indicated.</p>
				<?php } ?>
            <p>
                <label for="email">Please enter your email address:
				
				<?php if ($errors && in_array('missing_email', $errors)) { ?>
                        <span class="warning">An valid email address is required</span>
                    <?php } ?>
					<?php if ($errors && in_array('no_email', $errors)) { ?>
                        <span class="warning"><br>The email address you provided is not associated with an account<br>
						Please try another email address or use the link to the left to Register</span>
                    <?php } ?>
				</label>
                <input name="email" id="email" type="text"
				<?php if (isset($email) && !$errors['email']) {
                    echo 'value="' . htmlspecialchars($email) . '"';
                } ?>>
            </p>
			<p>
				<?php if ($errors && in_array('password', $errors)) { ?>
                        <span class="warning">The password supplied does not match the password for this email address. Please try again.</span>
                    <?php } ?>
                <label for="pw">Password: 
				
				<?php if ($errors && in_array('missing_password', $errors)) { ?>
                        <span class="warning">Please enter a password</span>
                    <?php } ?> </label>
                <input name="password" id="pw" type="password">
            </p>
			
            <p>
                <input name="send" type="submit" value="Login">
            </p>
		</fieldset>
        </form>
	</main>
<?php include './includes/footer.php'; ?>
