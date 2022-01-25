<?php 
	require './includes/header.php'; 	
	
	require_once '../../mysqli_connect.php';
	$sql= 'Select filename, caption FROM JJ_images';
	$result= mysqli_query($dbc, $sql);
	if($result){
		$numImages = mysqli_num_rows($result);
		mysqli_free_result($result);
	}
	else
		mysql_error($dbc);
	
	define ('COLS',2);
	define ('ROWS',3);
	$imgPerPage = COLS * ROWS;
	if (empty($_GET['page'])){
		$page=1;
		$startNum=0;
		$imgCounter=1;
	}
	else{
		$page = filter_var($_GET['page'], FILTER_VALIDATE_INT);
		$startNum=($page-1) * $imgPerPage;
		$imgCounter=($page-1) * $imgPerPage+1;
	}
	
	if ($numImages < $page*$imgPerPage)
		$endNum=$numImages;
	else
		$endNum=$startNum + $imgPerPage;
		
	
	$sql= "SELECT filename, caption FROM JJ_images LIMIT $startNum, $imgPerPage";
	$result = mysqli_query($dbc, $sql);
	if ($result)
		$row=mysqli_fetch_assoc($result);
	else
		mysqli_error($dbc);
	
	//Set main image
	if (isset($_GET['mainImage'])){
		$mainImage = filter_var($_GET['mainImage'], FILTER_SANITIZE_STRING);
	}
	else{
		$mainImage = $row['filename'];
	}
?>
	<main>
	    <h2>Japan Journey</h2>
		<p id="picCount">Displaying images <?php echo "$imgCounter to $endNum of $numImages";?></p>
        <section id="gallery">
            <table id="thumbs">
                <tr>
				<?php $pos=1;
				do { ?>					
					<!--This row is repeated-->
                    <td><a href="gallery.php?mainImage=<?= $row['filename']?>&amp;page=<?= $page; ?> "> <img src="images/thumbs/<?= $row['filename']; ?>" alt="<?= $row['caption']; ?>" width="80" height="54"></a></td>
                <?php
				if ($row['filename'] == $mainImage){
					$caption = $row['caption'];
				}
				if ($pos==COLS) {
					echo '</tr><tr>';
					$pos =1;
				}
				else{$pos++;}
				} while ($row=mysqli_fetch_assoc($result));
				
				while ($pos++ <= COLS){
					echo '<td>&nbsp;</td>';
				}
				?>
				</tr>
				<!-- Navigation links -->
				<?php
				if($page>1){
					$page--;
					echo "<tr><td> <a href=\"gallery.php?page=$page\"> &lt;prev</a></td>";
					}
				else
				 echo "<tr><td></td>";
			 
				for($i=1; $i <= COLS-2; $i++)
					echo "<td></td>";
				
				if ($imgCounter < $numImages){
					$page++;
					echo "<td> <a href=\"gallery.php?page=$page\"> next&gt;</a></td></tr>";
				}
				else{
					echo "<td></td></tr>";
				}
				mysqli_free_result($result);
				?>			
            </table>
			
            <figure id="main_image">
                <img src="images/<?= $mainImage; ?>" alt="<?= $caption; ?>" >
                <figcaption><?= $caption; ?></figcaption>
            </figure>
        </section>
		                                                                         
    </main>
<?php include './includes/footer.php'; ?>


