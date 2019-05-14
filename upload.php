<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Memteka</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<script async defer src="https://connect.facebook.net/lt_LT/sdk.js#xfbml=1&version=v3.2"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        footer{
            position:absolute;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <?php
			session_start();
			ob_start();
			include("includes/header.php");
			if(!isset($_SESSION['vartotojo_vardas'])) 	// jei jungiasi ne administratorius, grazina i index.php
			{
				header("Location: index.php");
			}
		?>
        <main>
            <div class="row-container">
                <div class="sidebar-column">
                    <aside id="sidebar">
                        <div id="sidebar-header">
                            <span id="sidebar-title">Kategorijos</span>
                        </div>
                        <ul class="category-list">
                        <?php
                            include('includes/config.php');
                            $dbc = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                            if(!$dbc){
                                die('Klaida! Negalima prisijungti prie duomenų bazės:' . mysqli_error($dbc));
                            }
                            $query = 'SELECT * FROM kategorijos';
                            $result = mysqli_query($dbc, $query);
                            while($row=mysqli_fetch_assoc($result)){
                                echo "<li class=\"category-item\"><a>" .$row['pavadinimas']. "</a></li>";			
                            } 
                        ?>
                        </ul>
                    </aside>
                </div>
                <div class="main-column">
                    <div class="meme-upload">
					<form method="post" enctype="multipart/form-data">
                            <label style="font-size: 17px"> Pasirinkite nuotrauką, kurią norite įkelti: </label><br>
							<label for="fileToUpload" class="custom-file-upload">
								<i class="fas fa-file-upload"></i> Failo pasirinkimas
							</label>
                            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/gif, image/jpeg, image/jpg"><br>
							<img id="preview" src="#" alt=" " style="width: 100%; height: 100%; " /><br>
                            <label style="font-size: 17px;"> Nuotraukos pavadinimas: </label>
                            <input class="upload-input" type="text" name="pavadinimas" id="pavadinimas" maxlength="255" required><br>
							<label style="font-size: 17px;line-height: 50px;"> Pasirinkite kategorijas: </label><br>
							<?php
								$query = 'SELECT * FROM kategorijos';
								$result = mysqli_query($dbc, $query);
								while($row=mysqli_fetch_assoc($result)){
									$kategorijos_pavadinimas = $row['pavadinimas'];
									echo "<label class=\"checkbox-container\"> <input type='checkbox' name='checkbox-", $kategorijos_pavadinimas, "'> <span class=\"checkmark\"></span>", $kategorijos_pavadinimas, "</label>";			
								}
							?>
                            <input class="upload-button" type="submit" value="Įkelti memą" name="submit">
                        </form>
                    </div>
                </div>
                <div class="sidebar-column">
				<div class="fb-page" data-href="https://www.facebook.com/memtekalt" data-tabs="timeline" data-width="500" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/memtekalt" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/memtekalt">Memteka</a></blockquote></div>
                    </div>
                </div>
            </div>
        </main>
        <?php include("includes/footer.php"); ?>
    </div>
</body>
<script src="js/preview.js"></script>
</html>

<?php	

	if(isset($_POST["submit"])) {
		$message = '';
		$date = date("Y-m");
		if(!file_exists("uploads/".$date)){
			mkdir("uploads/".$date);
		}
		$katalogas = "uploads/".$date."/"; // nurodom kataloga, kuriame bus patalpintas failas
		$dateUpload = date('Y-m-d H:i:s', time());
		$failas = $katalogas . sha1($dateUpload)  . "." . basename($_FILES["fileToUpload"]["type"]); // aprasomas kelias iki failo
		echo "<script type='text/javascript'>alert('$failas');</script>"; // issoka alert
		$uploadOk = 1; // pradzioje klaidu nera, todel 1 (kai bus klaida bus 0)
		$imageFileType = strtolower(pathinfo($failas,PATHINFO_EXTENSION));
		
		// Tikrinti ar failas yra nuotrauka
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check != false) { // failas yraa nuotrauka
			$uploadOk = 1;
		} else { // failas nera nuotrauka
			$message = 'Atsiprašome, tačiau įkeltas failas nėra nuotrauka.';
			echo "<script type='text/javascript'>alert('$message');</script>"; // issoka alert
			$uploadOk = 0;
		}
		// Tikrinti failo dydi
		if ($_FILES["fileToUpload"]["size"] > 200000) { // 200KB max
			$message = 'Atsiprašome, failas yra per didelis (maks. 200KB).';
			echo "<script type='text/javascript'>alert('$message');</script>"; // issoka alert
			$uploadOk = 0;
		}
		// Leisti atitinkamu formatu failus
		if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			$message = 'Atsiprašome, leidžiama įkelti tik JPG, JPEG ir GIF formato failus.';
			echo "<script type='text/javascript'>alert('$message');</script>"; // issoka alert
			$uploadOk = 0;
		}
		// Tikrinti ar $uploadOk yra 0 del klaidu
		if ($uploadOk == 0) {
		// Jei viskas gerai, bandyti ikelti faila
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $failas)) {
				$memePostDate = date("Y-m-d H:i:s");
				$pavadinimas = $_POST['pavadinimas'];
				date_default_timezone_set('Europe/Vilnius');
				$vartotojas = $_SESSION['vartotojo_vardas']; // ikele admin
				
				$query = "INSERT INTO memai (pavadinimas, nuoroda, tasku_kiekis, komentaru_kiekis, data, fk_vartotojo_vardas)
						  VALUES ('$pavadinimas', '$failas', '0', '0', '$data', '$vartotojas')";
				
				if(mysqli_query($dbc, $query)) {
					$last_id = $dbc->insert_id; // gauname iterpto memo ID
					$query_categories = "SELECT * FROM kategorijos";
					$result = mysqli_query($dbc, $query_categories);
					while($row=mysqli_fetch_assoc($result)){
						if (isset($_POST["checkbox-{$row['pavadinimas']}"])) { // patikriname ar checkbox nera null
							if($_POST["checkbox-{$row['pavadinimas']}"] == 'on'){ // jei checkbox'ai buvo pazymeti, priskiriame mema kategorijoms
								$query_cat_meme = "INSERT INTO memai_kategorijos (fk_kategorijos_pavadinimas, fk_memo_id) VALUES ('{$row['pavadinimas']}', '$last_id')";
								mysqli_query($dbc, $query_cat_meme);
							}
						}
					}
					$message = 'Failą įkelti pavyko sėkmingai!';
					echo "<script type='text/javascript'>alert('$message');</script>"; // issoka alert
					//echo "<script type='text/javascript'>alert('$message');location='index.php';</script>"; // issoka alert, po to nukreipia atgal i index.php
				} else {
					$message = 'Atsiprašome, įkelti nepavyko. Neteisinga duomenų bazės užklausa.';
					echo "<script type='text/javascript'>alert('$message');</script>"; // issoka alert
				}
			} else {
				$message = 'Atsiprašome, keliant failą įvyko klaida.';
				echo "<script type='text/javascript'>alert('$message');</script>"; // issoka alert
			}
		}
	}
?>