<?php
	session_start();
	if(!isset($_SESSION['vartotojo_vardas'])) 	// jei jungiasi ne administratorius, grazina i index.php
	{
		header("Location: index.php");
	} else {
		session_destroy();
		header('Location: index.php');
	}
?>