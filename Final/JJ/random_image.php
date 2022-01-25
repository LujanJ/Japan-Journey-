<?php 	
	require_once('../../mysqli_config.php');
	$sql = 'SELECT image_id FROM JJ_images';
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$numrows = $stmt->rowCount();
	$i = mt_rand(1, $numrows); //Mersenne Twister algorithm
	$sql2 = 'SELECT filename, caption FROM JJ_images WHERE image_id = :imageid';
	$stmt2 = $conn->prepare($sql2);
	$stmt2->bindValue(':imageid', $i);
	$stmt2->execute();
	$result = $stmt2->fetch();
	$image = $result['filename'];
	$caption = $result['caption'];
	$imagePath = 'images/'.$image;
	if (file_exists($imagePath)){
		$imageSize=getimagesize($imagePath);
		}
  } catch (PDOException $e) { 
		echo $e->getMessage(); 
		exit();
	}
?>
