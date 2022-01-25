<?php 
define('MAX_SIZE', 350);  //350x350 is the biggest size for a "main" image on the gallery page
require 'includes/header.php';

if(isset($_POST['submit'])) {
	if(isset($_FILES['site_img'])){
		$filename= $_FILES['site_img']['name'];
		$destination = $_SERVER['CONTEXT_DOCUMENT_ROOT']."/lab5/JJ/images/$filename";
		if(move_uploaded_file($_FILES['site_img']['tmp_name'], $destination )){
			$img = getimagesize("./images/$filename");
			$width= $img[0];
			$height= $img[1];
			$type = $img[mime];
			if ($width <= MAX_SIZE && $height <= MAX_SIZE) {
				$ratio = 1;
			} elseif ($width > $height) {
				$ratio = MAX_SIZE/$width;
			} else {
				$ratio = MAX_SIZE/$height;
			}
			$new_w = round($width * $ratio);
			$new_h = round($height * $ratio);
			$shortType = substr($type, 6);  //strip off MIME: image/
			if ($shortType=='gif') {
				$resource = imagecreatefromgif($destination);
			}elseif ($shortType=='png') {
				$resource = imagecreatefrompng($destination); 
			} else {
				$resource = imagecreatefromjpeg($destination);
			}
			$resized = imagecreatetruecolor($new_w, $new_h);
			imagecopyresampled($resized, $resource, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
			$new_destination = "./images/$filename";
			if ($shortType == 'gif') {
				imagegif($resized, $new_destination);
			} elseif ($shortType == 'png') {
				imagepng($resized, $new_destination);
            } else {
				imagejpeg($resized, $new_destination);
			}
			
			//Send image data to db:
			if(isset($_POST['caption']))
				$caption = filter_var(trim($_POST['caption']), FILTER_SANITIZE_STRING);
			else
				$caption = NULL;
			require_once ('../../../../mysqli_connect.php'); // Connect to the db.
			$sql = "INSERT into JJ_images(filename, caption) VALUES (?, ?)";
			$stmt = mysqli_prepare($dbc, $sql);
			mysqli_stmt_bind_param($stmt, 'ss', $filename, $caption);
			mysqli_stmt_execute($stmt);
			if (mysqli_stmt_affected_rows($stmt)){
				echo "<main><h2>We have saved the new item</h2>";
				echo "<img src = './images/$filename'></main>";
				mysqli_free_result($stmt);
			}
			else {
				echo"<main><h3>There was a problem saving to the database</h3></main>";
				include 'includes/footer.php';
				exit;
			}
		}
		elseif ($_FILES['site_img']['error'] > 0) {
			echo '<p class="error">The file could not be uploaded because: <strong>';

			// Print a message based upon the error.
			switch ($_FILES['site_img']['error']) {
				case 1:
					echo 'The file exceeds the upload_max_filesize setting in php.ini.';
					break;
				case 2:
					echo 'The file exceeds the MAX_FILE_SIZE setting in the HTML form.';
					break;
				case 3:
					echo 'The file was only partially uploaded.';
					break;
				case 4:
					echo 'No file was uploaded.';
					break;
				case 6:
					echo 'No temporary folder was available.';
					break;
				case 7:
					echo 'Unable to write to the disk.';
					break;
				case 8:
					echo 'File upload stopped.';
					break;
				default:
					echo 'A system error occurred.';
					break;
			} // End of switch.
			echo '</strong></p>';
		} // End of error IF.
		else {
			echo"<main><h3>Some unknown error has occurred.</h3></main>";
			//exit;
		}
	} //isset $_FILES
	include 'includes/footer.php';
	//release the uploaded file resource
	if(file_exists($_FILES['site_img']['tmp_name']) && is_file($_FILES['site_img']['tmp_name']))
				unlink($_FILES['site_img']['tmp_name']);
	exit;
}
?>
	<main>
	<form enctype="multipart/form-data" action="random_image.php" method="post">
		<p><label for="image">Upload image:</label> <input type="file" name="image" id="image"></p> 
		<p><input type="submit" name="upload" id="upload" value="Upload"> </p>
	</form>
		
	</main>
	<?php // Include the footer and quit the script:
	include ('./includes/footer.php'); 
	?>
	