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
            position:absolute;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <?php
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
							ob_start();
							$postId = htmlentities($_GET['postId']); // paimame postId
							
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
                    <?php
						$query = "SELECT * FROM memai WHERE id=$postId";
						$result = mysqli_query($dbc, $query);
						while($row=mysqli_fetch_assoc($result))
						{	
					?>				
                    <div class="meme-post">
                        <div class="meme-content">
							<h3 class="meme-title">
								<form method="post" enctype="multipart/form-data">
									<input type="text" name="edit_meme-title" id="edit_meme-title" maxlength="255" value="<?php echo htmlentities($row['pavadinimas']);?>" style="width:90%" required />
									<button type="submit" name="submit" style="border:none; background:none; width:auto; padding:0;">
										<i class="fas fa-check" style="color:green; cursor:pointer; font-size:20px"></i>
									</button>
								</form>
							</h3>
                        </div>
                        <div class="meme-image">
                            <img src="<?php echo htmlentities($row['nuoroda']);?>" alt="Smiley face">
                        </div>
                        <p class="post-meta">
                            <a class="point badge-evt">
                                <img src="images/arrows.png" alt="Upvotes" style="width:14px;height:14px;">
                                    <?php
										echo htmlentities($row['tasku_kiekis']), ' ta&#353k&#371';
									?>
                            </a>
                            <a class="comment badge-evt">
                                <i class="fas fa-comment"></i>
									<?php
										echo htmlentities($row['komentaru_kiekis']), " komentar&#371";
									?>
                            </a>
                        </p>
                    </div>
					<?php
						} // cia uzdarome meme_post while cikla
					?>
                </div>
                <div class="sidebar-column">
                    <div style="height: 200px; background: white;">
                    </div>
                </div>
            </div>
        </main>
        <?php include("includes/footer.php"); ?>
    </div>
</body>
</html>

<?php
	if(isset($_POST["submit"])) {
		$naujas_pavadinimas=$_POST['edit_meme-title'];
		
		if ($sql = $dbc->prepare("UPDATE memai SET pavadinimas = ? WHERE id = ?")) {
			// Bind the variables to the parameter as strings. 
			$sql->bind_param("ss", $naujas_pavadinimas, $postId);
		 
			// Execute the statement.
			$sql->execute();
		 
			// Close the prepared statement.
			$sql->close();
		}
		
		echo $sql;
		
		//$sql = "UPDATE memai SET pavadinimas='$naujas_pavadinimas' WHERE id='$postId'";

		//if (mysqli_multi_query($dbc, $sql)) {
		//	mysqli_close($dbc);
		//	$message="Pavadinimas sėkmingai atnaujintas!";
		//	echo "<script type='text/javascript'>alert('$message');location='edit.php?postId=$postId';</script>"; // issoka alert, po to refreshina
			
			//header("Location: edit.php?postId=$postId"); //Jei viskas gerai, grazina atgal
		//	exit;
		//} else {
		//	echo "Klaida redaguojant įrašą";
		//}
	}
?>