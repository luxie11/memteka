<?php
	session_start();

	$katalogas = "uploads/"; // nurodom kataloga, kuriame bus patalpintas failas
	$failas = $katalogas . basename($_FILES["fileToUpload"]["name"]); // aprasomas kelias iki failo
	$uploadOk = 1; // pradzioje klaidu nera, todel 1 (kai bus klaida bus 0)
	$imageFileType = strtolower(pathinfo($failas,PATHINFO_EXTENSION));
	// Tikrinti ar failas yra nuotrauka
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check != false) {
			echo "Failas yra nuotrauka - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "Failas nėra nuotrauka.";
			$uploadOk = 0;
		}
	}
	// Tikrinti ar failas egzistuoja
	if (file_exists($failas)) {
		echo "Atsiprašome, failas jau egzistuoja.";
		$uploadOk = 0;
	}
	// Tikrinti failo dydi
	if ($_FILES["fileToUpload"]["size"] > 200000) { // 200KB max
		echo "Atsiprašome, failas yra per didelis.";
		$uploadOk = 0;
	}
	// Leisti atitinkamu formatu failus
	if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
		echo "Atsiprašome, leidžiama įkelti tik JPG, JPEG ir GIF formato failus.";
		$uploadOk = 0;
	}
	// Tikrinti ar $uploadOk yra 0 del klaidu
	if ($uploadOk == 0) {
		echo "Atsiprašome, failo įkelti nepavyko.";
	// Jei viskas gerai, bandyti ikelti faila
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $failas)) {
			echo "Failas ". basename( $_FILES["fileToUpload"]["name"]). " sėkmingai patalpintas.";
			
			$pavadinimas = $_POST['pavadinimas'];
			date_default_timezone_set('Europe/Vilnius');
			$data = date('Y-m-d H:i:s', time());
			$vartotojas = "admin"; // ikele admin, bet geriau padaryti su $_SESSION['vartotojo_vardas']
			
			require('includes/config.php');
			$dbc=mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
			if(!$dbc){
				die("Negaliu prisijungti prie MySQL:".mysqli_error($dbc));
			}
			
			$query = "INSERT INTO memai (pavadinimas, nuoroda, tasku_kiekis, komentaru_kiekis, data, fk_vartotojo_vardas)
					  VALUES ('$pavadinimas', '$failas', '0', '0', '$data', '$vartotojas')";
			
			if(mysqli_query($dbc, $query))
			{
				echo "Viskas pavyko sekmingai";
				//echo "<script type='text/javascript'>alert('$message');location='index.php';</script>"; // issoka alert, po to nukreipia atgal i index.php
				} else {
					echo "Kazkas nepavyko su uzklausa";
					//header("Location: create_course.php");
				}
			
		} else {
			echo "Atsiprašome, keliant failą įvyko klaida.";
		}
	}
?>
