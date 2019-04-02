<?php
session_start();
if(!isset($_SESSION['vartotojo_vardas'])) { 	// jei jungiasi ne administratorius, grazina i index.php
	header("Location: index.php");
} else {
	$postId = $_GET['postId']; // memo id

	require_once("includes/config.php");
	$dbc=mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); // prisijungia prie DB serverio
	if(!$dbc) {
		die("Negaliu prisijungti prie MySQL:".mysqli_error($dbc));
	}

	// istrinti su memu susijusias kategorijas
	if($sql = $dbc->prepare("DELETE FROM memai_kategorijos WHERE fk_memo_id = ?")) {
		$sql->bind_param("i", $postId);
		$sql->execute();
		$sql->close();
	}
	// istrinti su memu susijusius komentarus
	if($sql = $dbc->prepare("DELETE FROM komentarai WHERE fk_memo_id = ?")) {
		$sql->bind_param("i", $postId);
		$sql->execute();
		$sql->close();
	}
	// istrinti mema
	if($sql = $dbc->prepare("DELETE FROM memai WHERE id = ?")) {
		$sql->bind_param("i", $postId);
		$sql->execute();
		$sql->close();
	}

	header('Location: index.php'); //Jei viskas gerai, grazina atgal
	exit;
}
?>