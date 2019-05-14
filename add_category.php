<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Memteka</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script async defer src="https://connect.facebook.net/lt_LT/sdk.js#xfbml=1&version=v3.2"></script>
<style>
	footer{
		position: absolute;
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
                            <label style="font-size: 17px"> Įrašykite naujos kategorijos pavadinimą: </label>
                            <input class="new-category-input" type="text" name="kategorija" id="kategorija" maxlength="255" required><br>
                            <input class="new-category-button" type="submit" value="Patvirtinti" name="submit">
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
</html>

<?php
if (isset($_POST['submit'])) {
	$kategorija = mysqli_real_escape_string($dbc, $_POST['kategorija']);

	if($stmt = $dbc->prepare("INSERT INTO kategorijos (pavadinimas) VALUES (?)")) {
		$stmt->bind_param("s", $kategorija);
		$stmt->execute();
		$stmt->close();
		
		$message = "Kategorija sėkmingai sukurta!";
		echo "<script type='text/javascript'>alert('$message');location='index.php';</script>";
	}
}
?>