<?php //This page checks for required content, errors, and provides sticky output
	require_once('../../../mysqli_connect.php');
	require 'includes/header.php';
	if (isset($_POST['send'])) {
	$errors = array();
	
	$firstname = filter_var(trim($_POST['firstname']), FILTER_SANITIZE_STRING); //returns a string
	if (empty($firstname)) 
		$errors[]='firstname';
	
	$lastname = filter_var(trim($_POST['lastname']), FILTER_SANITIZE_STRING); //returns a string
	if (empty($lastname)) 
		$errors[]='lastname';
	
	$valid_email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);	//returns a string or null if empty or false if not valid	
	if (trim($_POST['email']==''))
		$errors[] = 'missing_email';
	elseif (!$valid_email)
		$errors[] = 'invalid_email';
	else 
		$email = $valid_email;
	
	$password1 = filter_var(trim($_POST['password1']), FILTER_SANITIZE_STRING);
	$password2 = filter_var(trim($_POST['password2']), FILTER_SANITIZE_STRING);
	// Check for a password:
	if (empty($password1) || empty($password2)) 
		$errors[]='missing_password';
	elseif ($password1 !== $password2) 
			$errors[] = 'no_match';
	else $password = $password1;
	
	$accepted = filter_var($_POST['terms']);
	if (empty($accepted) || $accepted !='accepted')
		$errors[] = 'accepted';
	
	if (!$errors) {
		require_once ('../../../mysqli_connect.php'); // Connect to the db.
		$sql = "SELECT * FROM JJ_reg_users WHERE emailAddr = ?";
		$stmt = mysqli_prepare($dbc, $sql);
		mysqli_stmt_bind_param($stmt, 's', $email);
		mysqli_stmt_execute($stmt);
		$result=mysqli_stmt_get_result($stmt);
		$rows = mysqli_num_rows($result);
		mysqli_free_result($stmt);
		if ($rows==0) { //email not found, add user		
			$sql2 = "INSERT into JJ_reg_users (firstName, lastName, emailAddr, pw) VALUES (?, ?, ?, ?)";
			$stmt2 = mysqli_prepare($dbc, $sql2);
			$pw_hash= password_hash($password, PASSWORD_DEFAULT);
			mysqli_stmt_bind_param($stmt2, 'ssss', $firstname, $lastname, $email, $pw_hash);
			mysqli_stmt_execute($stmt2);
			if (mysqli_stmt_affected_rows($stmt2)){
				echo "<main><h2>Thank you for registering $firstname</h2><h3>We have saved your information</h3></main>";
				mysqli_free_result($stmt2);
			}
			else {
				echo "<main><h2>We're sorry. We are unable to add your account at this time.</h2><h3>Please try again later</h3></main>";
			 }
			include 'includes/footer.php'; 
			exit; 
		}
		elseif ($rows==1) //email found
			$errors[]='duplicate';
		else { //some other error
			echo "We are unable to process your request at  this  time. Please try again later.";
			include 'includes/footer.php'; 
			exit;
		}
	}// no errors 
	   
	} //isset
?>

	<main>
        <h2>Japan Journey</h2>
        <p>Ut enim ad minim veniam, quis nostrud exercitation consectetur adipisicing elit. Velit esse cillum dolore ullamco laboris nisi in reprehenderit in voluptate. Mollit anim id est laborum. Sunt in culpa duis aute irure dolor excepteur sint occaecat.</p>
        <form method="post" action="create_acct.php">
			<fieldset>
				<legend>Please Register:</legend>
				<?php if ($errors) { ?>
				<p class="warning">Please fix the item(s) indicated.</p>
				<?php } ?>
            <p>
                <label for="fn">First Name: 
				<?php if ($errors && in_array('firstname', $errors)) { ?>
                        <span class="warning">Please enter your first name</span>
                    <?php } ?> </label>
                <input name="firstname" id="fn" type="text"
				 <?php if (isset($firstname)) {
                    echo 'value="' . htmlspecialchars($firstname) . '"';
                } ?>
				>
            </p>
			<p>
                <label for="ln">Last Name: 
				<?php if ($errors && in_array('lastname', $errors)) { ?>
                        <span class="warning">Please enter your last name</span>
                    <?php } ?> </label>
                <input name="lastname" id="ln" type="text"
				 <?php if (isset($lastname)) {
                    echo 'value="' . htmlspecialchars($lastname) . '"';
                } ?>
				>
            </p>
            <p>
                <label for="email">Email: 
				<?php if ($errors && in_array('missing_email', $errors)) { ?>
                        <span class="warning">Please enter your email address</span>
                    <?php } ?>
				<?php if ($errors && in_array('invalid_email', $errors)) { ?>
                        <span class="warning">The email address you provided is not valid</span>
                    <?php } ?>
				<?php if ($errors && in_array('duplicate', $errors)) { ?>
                        <span class="warning">The email address you provided already exists in the database.<br>Please enter another email address or Login using the menu to the left</span>
                    <?php } ?>
				</label>
                <input name="email" id="email" type="text"
				<?php if (isset($email) && !$errors['email']) {
                    echo 'value="' . htmlspecialchars($email) . '"';
                } ?>>
            </p>
			<p>
				<?php if ($errors && in_array('no_match', $errors)) { ?>
                        <span class="warning">The entered passwords do not match. Please try again.</span>
                    <?php } ?> 
                <label for="pw1">Password: 
				
				<?php if ($errors && in_array('missing_password', $errors)) { ?>
                        <span class="warning">Please enter a password</span>
                    <?php } ?> </label>
                <input name="password1" id="pw1" type="password">
            </p>
			<p>
                <label for="pw2">Confirm Password: </label>
                <input name="password2" id="pw2" type="password">
            </p>
         
            <p>
			<?php if ($errors && in_array('accepted', $errors)) { ?>
                        <span class="warning">You must agree to the terms</span><br>
                    <?php } ?>
                <input type="checkbox" name="terms" value="accepted" id="terms"
				     <?php if ($accepted) {
                                echo 'checked';
                            } ?>
				>
                <label for="terms">I accept the terms of using this website</label>
            </p>
            <p>
                <input name="send" type="submit" value="Register">
            </p>
		</fieldset>
        </form>
	</main>
<?php include './includes/footer.php'; ?>
