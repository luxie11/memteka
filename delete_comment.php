<?php
session_start();
if(!isset($_SESSION['vartotojo_vardas'])) 	// jei jungiasi ne administratorius, grazina i index.php
{
	header("Location: index.php");
} else {
	$commentId = $_GET['commentId']; // komentaro id
	$postId = $_GET['postId'];		// memo id

	require_once("includes/config.php");
	$dbc=mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); // prisijungia prie DB serverio
	if(!$dbc) {
		die("Negaliu prisijungti prie MySQL:".mysqli_error($dbc));
	}
	$sql = "DELETE FROM komentarai WHERE id = '$commentId'; UPDATE memai SET komentaru_kiekis=komentaru_kiekis-1 WHERE id='$postId';";

	if (mysqli_multi_query($dbc, $sql)) {
		mysqli_close($dbc);
		header('Location: comments.php?postId='.$postId); //Jei viskas gerai, grazina atgal
		exit;
	} else {
		echo "Klaida trinant komentarą";
	}
}

?>