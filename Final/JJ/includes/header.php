<?php 
	session_start();
	include './includes/title.php'; 
?>	
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>JML<?php if(isset($title)) {echo "&mdash;$title";} ?></title>
    <link href="styles/journey.css" rel="stylesheet" type="text/css">
</head>

<body>
<header>
    <h1>JML</h1> 
</header>
<div id="wrapper">
    <?php require './includes/menu.php'; ?>