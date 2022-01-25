<?php 
	require './includes/header.php';
	require_once '../../mysqli_connect.php';
	$sql = 'SELECT * FROM JJ_images';
	$result = mysqli_query($dbc, $sql);
	if (!$result) {
		echo "We are unable to process your request at  this  time. Please try again later.";
			include 'includes/footer.php'; 
			exit;
	}
	
	function shortTitle ($title){
		$title = substr($title, 0, -4); #remove the .ext from each title
		$title = str_replace('_', ' ', $title); #replace underscores with blanks
		$title = ucwords($title); #capitalize each word
		return $title;
	}
		
	?>
  <main>
	<h2>Images</h2>
	<h3>Each of our lovely images may be purchased for you to enjoy in your home or to give as a gift</h3>     
    <table>
        <tr>
            <th>Title</th>
			<th>Image</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
			<tr>
                
                <td><?php echo (shortTitle($row['filename'])) ?></td>
                <td><?php echo '<img src =  "images/'. $row['filename'].  '">';?></td>
            </tr>
			
		
		<?php } //end while loop    <img src = " " alt = " "?>
    </table>
  </main>
<?php include 'includes/footer.php'; ?>