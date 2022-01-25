<?php
require_once '../../mysqli_connect.php';
$sql = 'SELECT * FROM JJ_images';
$result = mysqli_query($dbc, $sql);
if (!$result) {
    $error = mysqli_stmt_error();
} else {
    $numRows = mysqli_num_rows($result);
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Test</title>
</head>
<body>
<?php
if (isset($error)) {
    echo "<p>$error</p>";
} else {
    echo "<p>A total of $numRows records were found.</p>";
?>
	<table>
        <tr>
            <th>Image ID</th>
            <th>Filename</th>
            <th>Caption</th>
        </tr>
        <?php 
			
			while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['image_id'] ?></td>
                <td><?php echo $row['filename'] ?></td>
                <td><?php echo $row['caption'] ?></td>
            </tr>
        <?php } ?>  <!-- end foreach -->
    </table>
<?php } ?> <!-- end else -->
</body>
</html>
