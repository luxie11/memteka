<?php
//if() 	// jei jungiasi ne administratorius, grazina i index.php
//{
//	header("Location: index.php");
//}

$memeId = $_GET['memeId']; // memo id

require_once("includes/config.php");
$dbc=mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME); // prisijungia prie DB serverio
if(!$dbc) {
	die("Negaliu prisijungti prie MySQL:".mysqli_error($dbc));
}

$sql = "DELETE FROM komentarai WHERE fk_memo_id = '$memeId'; DELETE FROM memai WHERE id = '$memeId'";

if (mysqli_multi_query($dbc, $sql)) {
    mysqli_close($dbc);
    header('Location: index.php'); //Jei viskas gerai, grazina atgal
    exit;
} else {
    echo "Klaida trinant įrašą";
}

?>