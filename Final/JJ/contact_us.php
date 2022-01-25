<?php 
//This page checks for required content, errors, and provides sticky output
//Upon successful submission, data is sent to the database
require './includes/header.php';
if (isset($_GET['send'])) {
	$errors = array();
	
	$name = filter_var(trim($_GET['name']), FILTER_SANITIZE_STRING); //Keep quotes in name for apostrophes
	if (empty($name)) 
		$errors['name'] = "Name is required:";

	//Remove illegal characters
	$email= filter_var(trim($_GET['email']), FILTER_SANITIZE_EMAIL);
	if (empty($email))
		$errors['email'] = 'An email address is required:';
	else {
		//check validity
		$valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);	//returns a string or null if empty or false if not valid	
		if ($valid_email)
			$email = $valid_email;
		else
			$errors['email'] = 'A valid email is required:';
	}
	
	$comments = filter_var(trim($_GET['comments'])); //returns a string
	$comments = nl2br($comments, false); //Keep line breaks and use <br> tags rather than <br />
	
	if ($_GET['newsletter'] == 'yes') 
		$newsletter = 1;
	elseif ($_GET['newsletter'] == 'no')
		$newsletter = 0;
	else 
		$errors['newsletter'] = 'Select your subscription preference:';
		
	if(isset($_GET['interests'])){
		$checked=filter_var_array($_GET['interests'], FILTER_SANITIZE_STRING);
		if (empty($checked))
			$errors['interests'] = 'Please select at least one interest:';
	}
	else 
		$errors['interests'] = 'Please select at least one interest:';
		
	
	$howhear = filter_var($_GET['howhear'], FILTER_SANITIZE_STRING);
	if (empty($howhear))
		$errors['howhear'] = 'Please select how you heard about us:';
		
	$terms = filter_var($_GET['terms'], FILTER_SANITIZE_STRING);
	if (empty($terms) || $terms != 'accepted')
		$errors['terms']= 'You must accept our terms of service:';
	else	
		$terms = 'accepted';	
	
	
	if (!$errors) {
	//Add contact to database
		//Split name into first, last
		$tempName = explode(" ",$name); //separates a string based on the first argument, creates an array of strings 
		$firstName= $tempName[0];
		if (!empty($tempName[1])) 
			$lastName = $tempName[1];
		else
			$lastName = null;
		//Process the interests 
		$anime=0;
		$arts=0;
		$judo=0;
		$lang=0;
		$sci=0;
		$travel=0;
		foreach ($checked as $box){
			if($box=='anime')
				$anime=1;	
			if($box=='arts')
				$arts=1;
			if($box=='judo')
				$judo=1;
			if($box=='lang')
				$lang=1;
			if($box=='sci')
				$sci=1;
			if($box=='travel')
				$travel=1;
		}
		//Connect to the database and insert the data
		require_once '../../mysqli_connect.php';
		$sql = "SELECT emailAddr FROM JJ_contacts where emailAddr = ?";
		$stmt = mysqli_prepare($dbc, $sql);
		mysqli_stmt_bind_param($stmt, 's', $email);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_fetch($stmt);
		$rows = mysqli_stmt_num_rows($stmt);
		mysqli_free_result($stmt);
		
		if ($rows != 0){
			echo "<main><h2>We're sorry, we are unable to process your email, It is in use.</h2>  <h2>Please try a different email or log in to make changes</h2></main>";
		}// end rows not 0
		
		if ($rows==0){
		$sql2 = "Insert into JJ_contacts(firstName, lastName, emailAddr, comments, newsletter, howHear, anime, arts, judo, lang, sci, travel) Values (?,?,?,?,?,?,?,?,?,?,?,?)";
		//password hash $pw_hash = password_hash($pw, PASSWORD_DEFAULT);
		$stmt2 = mysqli_prepare($dbc, $sql2);
		mysqli_stmt_bind_param($stmt2, 'ssssisiiiiii', $firstName, $lastName, $email, $comments, $newsletter, $howhear, $anime, $arts, $judo, $lang, $sci, $travel);
		mysqli_stmt_execute($stmt2);	
		
		if(mysqli_stmt_affected_rows($stmt2))		
		echo "<main><h2>Thank you $firstName $lastName for contacting us</h2><h3>We have saved your information</h3></main>";
		
		else 			
		echo "<main><h2>We're sorry, we are unable to process your request at this time.</h2>  <h2>Please try again later</h2></main>";			
		
		} //End no rows
		include 'includes/footer.php'; 
		exit;
		
		} # end no errors
		
		 
} #end isset($_GET['send'])
?>

	<main>
        <p>Ut enim ad minim veniam, quis nostrud exercitation consectetur adipisicing elit. Velit esse cillum dolore ullamco laboris nisi in reprehenderit in voluptate. Mollit anim id est laborum. Sunt in culpa duis aute irure dolor excepteur sint occaecat.</p>
        <form method="get" action="contact_us.php">
			<fieldset>
				<legend>Contact Us</legend>
				<?php if ($errors)
				  echo'<h2 class="warning">Please fix the item(s) indicated.</h2>';
				?>
            <p>
				<?php if ($errors['name']) echo '<span class="warning">'.$errors['name'].'</span>';?> 
                <label>Name  
					<input name="name" type="text"
					   <?php if(isset($name)) echo 'value="'.htmlspecialchars($name) . '"';?>> 
				</label>
            </p>
            <p>				
				<?php if ($errors['emailCheck']) echo '<span class="warning">'.$errors['emailCheck'].'</span>';?>
				<?php if ($errors['email']) echo '<span class="warning">'.$errors['email'].'</span>';?>
                <label>Email  
					<input name="email" type="text"
					   <?php if (isset($email) && !$errors['email']) echo 'value="' . htmlspecialchars($email) . '"';?>>
				</label>
            </p>
            <p>
                <label>Comments 
					<textarea name="comments" id="comments"><?php if(isset($comments)) echo htmlspecialchars($comments);?></textarea>
				</label>
            </p>
            <fieldset id="subscribe">
				<?php if ($errors['newsletter']) echo '<span class="warning">'.$errors['newsletter'].'</span>';?>
                <h2>Subscribe to newsletter?</h2>
				<p>
					<label>
					<input name="newsletter" type="radio" value="yes"
					<?php if (isset($newsletter)&& $newsletter==1) echo " checked"; ?>>
					Yes
					</label>
				</p>
				<p>
					<label>
					<input name="newsletter" type="radio" value="no"
					<?php if (isset($newsletter)&& $newsletter==0) echo " checked"; ?>>
					No</label>
                </p>
            </fieldset>
            <fieldset id="interests">
                <p>
					<?php if ($errors['interests'])echo '<span class="warning">'.$errors['interests'].'</span><br>';?>
					Interests in Japan
                </p>
				<div>
                    <p><label>
                        <input type="checkbox" name="interests[]" value="anime"
						 <?php if ($checked && in_array('anime', $checked)) echo ' checked'; ?>>
                        Anime/manga
						</label>
                    </p>
                    <p><label>
                        <input type="checkbox" name="interests[]" value="arts"
						<?php if ($checked && in_array('arts', $checked)) echo ' checked'; ?>>
                        Arts &amp; crafts
						</label>
                    </p>
                    <p><label>
                        <input type="checkbox" name="interests[]" value="judo"
						<?php if ($checked && in_array('judo', $checked)) echo ' checked'; ?>>
                        Judo, karate, etc
						</label>
                    </p>
                </div>
                <div>
                    <p><label>
                        <input type="checkbox" name="interests[]" value="lang"
						<?php if ($checked && in_array('lang', $checked)) echo ' checked'; ?>>
                        Language/literature
						</label>
                    </p>
					<p><label>
                        <input type="checkbox" name="interests[]" value="sci"
						<?php if ($checked && in_array('sci', $checked)) echo ' checked'; ?>>
                        Science &amp; technology
						</label>
                    </p>
                    <p><label>
                        <input type="checkbox" name="interests[]" value="travel"
						<?php if ($checked && in_array('travel', $checked)) echo ' checked';?>>
						Travel
						</label>
                    </p>
                </div>
            </fieldset>
            <p>
				<?php if ($errors['howhear']) echo '<span class="warning">'.$errors['howhear'].'</span><br>';?>
                <label>How did you hear of Japan Journey? <br>
                <select name="howhear">
                    <option value="">Select one</option>
                    <option value="apress" <?php if ($howhear == 'apress') echo ' selected'; ?>>Apress</option>
                    <option value="friend" <?php if ($howhear == 'friend') echo ' selected'; ?>>Recommended by a friend</option>
                    <option value="search" <?php if ($howhear == 'search') echo ' selected'; ?>>Search engine</option>
                </select>
				</label>
            </p>
            <p>
			<?php if ($errors['terms']) echo '<span class="warning">'.$errors['terms'].'</span><br>';?>
			<label>
                <input type="checkbox" name="terms" value="accepted"<?php if ($terms=='accepted') echo ' checked'; ?>>
                I accept the terms of using this website
			</label>
            </p>
            <p>
                <input name="send" type="submit" value="Send message">
				<input type="reset" value="Reset">
            </p>
		</fieldset>
        </form>
	</main>
<?php include './includes/footer.php'; ?>
