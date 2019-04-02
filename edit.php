<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Memteka</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script async defer src="https://connect.facebook.net/lt_LT/sdk.js#xfbml=1&version=v3.2"></script>
</head>
<body>
    <div id="wrapper">
        <?php
		include("includes/header.php");
		ob_start();
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
						$stmt1 = $dbc->prepare("SELECT * FROM memai WHERE id = ?");
					
						if($stmt1 && $stmt1 -> bind_param('s', $postId) && $stmt1 -> execute() && $stmt1 -> store_result() && $stmt1 -> bind_result($id, $pavadinimas, $nuoroda, $tasku_kiekis, $komentaru_kiekis, $data, $fk_vartotojo_vardas)) {
							while ($stmt1 -> fetch()) {
					?>				
                    <div class="meme-post">
                        <div class="meme-content">
							<h3 class="meme-title">
								<form method="post" enctype="multipart/form-data">
									<input type="text" name="edit_meme-title" id="edit_meme-title" maxlength="255" value="<?php echo htmlentities($pavadinimas);?>" style="width:90%" required />
									<button type="submit" name="submit" style="border:none; background:none; width:auto; padding:0;">
										<i class="fas fa-check" style="color:green; cursor:pointer; font-size:20px"></i>
									</button><br>
									
									<?php
										$query = 'SELECT * FROM kategorijos';
										$result = mysqli_query($dbc, $query);
										while($row=mysqli_fetch_assoc($result)){
											$kategorijos_pavadinimas = $row['pavadinimas'];
											$query2 = "SELECT * FROM memai_kategorijos WHERE fk_kategorijos_pavadinimas='$kategorijos_pavadinimas' AND fk_memo_id='$postId'";
											$num_rows = '0'; // nusinuliname eiluciu kieki pries tikrinant
											$result2 = mysqli_query($dbc, $query2);
											$num_rows = mysqli_num_rows($result2);
											if($num_rows == 1) {
												$isChecked='checked';
											} else {
												$isChecked='';
											}
											// jei gauta viena eilute rezultate, pazymima checked
											echo "<input type='checkbox' name='checkbox-", $kategorijos_pavadinimas, "' ", $isChecked, '> ', $kategorijos_pavadinimas, '<br>';
											
										}
									?>
								</form>
							</h3>
                        </div>
                        <div class="meme-image">
							<?php
								echo '<img src="'.htmlentities($nuoroda).'" alt="'.htmlentities($pavadinimas).'">';
							?>
                        </div>
                        <p class="post-meta">
                            <a class="point badge-evt">
                                <img src="images/arrows.png" alt="Upvotes" style="width:14px;height:14px;">
                                    <?php
										echo htmlentities($tasku_kiekis), ' ta&#353k&#371';
									?>
                            </a>
                            <a class="comment badge-evt">
                                <i class="fas fa-comment"></i>
									<?php
										echo htmlentities($komentaru_kiekis), ' komentar&#371';
									?>
                            </a>
                        </p>
                    </div>
					<?php
						}	// uzdarome meme-post while cikla
						$stmt1->close();
					} else {
						echo "Paruostos uzklausos klaida";
					}
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
			$sql->bind_param("ss", $naujas_pavadinimas, $postId);
			$sql->execute();
			$sql->close();
		}
		// kategoriju suzymejimas
		if($sql = $dbc->prepare("DELETE FROM memai_kategorijos WHERE fk_memo_id = ?")) {
			$sql->bind_param("i", $postId);
			$sql->execute();
			$sql->close();
		}
		
		$query_categories = "SELECT * FROM kategorijos";
		$result = mysqli_query($dbc, $query_categories);
		while($row=mysqli_fetch_assoc($result)){
			if($_POST["checkbox-{$row['pavadinimas']}"] == 'on'){ // jei checkbox'ai buvo pazymeti, priskiriame mema kategorijoms
				if($sql = $dbc->prepare("INSERT INTO memai_kategorijos (fk_kategorijos_pavadinimas, fk_memo_id) VALUES (?, ?)")){
					$sql->bind_param("si", $row['pavadinimas'], $postId);
					$sql->execute();
					$sql->close();
				}
			}
		}
		$message = "Pavadinimas ir kategorijos sėkmingai pakeistos!";
		echo "<script type='text/javascript'>alert('$message');location='edit.php?postId=$postId';</script>";
	}
?>