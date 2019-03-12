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
			
			require('includes/config.php');
			$dbc=mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
			if(!$dbc){
				die("Negaliu prisijungti prie MySQL:".mysqli_error($dbc));
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
								$query = "SELECT * FROM kategorijos";
								$result = mysqli_query($dbc, $query);
								while($row=mysqli_fetch_assoc($result))
								{						
							?>
			   
							<li class="category-item">
								<a><?php echo $row['pavadinimas']; ?></a>
							</li>
							<?php
								} // cia uzdarome category-item while cikla
							?>
                        </ul>
                    </aside>
                </div>
                <div class="main-column">
					<?php
						$query = "SELECT * FROM memai";
						$result = mysqli_query($dbc, $query);
						while($row=mysqli_fetch_assoc($result))
						{
							$id = $row['id']; // iskart prisiskiriame memo ID (reikalinga komentarams)
					?>
                        <div class="meme-post">
                                <div class="meme-content">
					<h3 class="meme-title">
                                    		<?php
							echo $row['pavadinimas'];
						?>
					</h3>
                                </div>
                                <div class="meme-image">
                                    <img src="<?php echo $row['nuoroda'];?>" alt="Smiley face">
                                </div>
                                <p class="post-meta">
                                    <a class="point badge-evt">
                                        <img src="images/arrows.png" alt="Upvotes" style="width:14px;height:14px;">
                                        <?php
											echo "${row['tasku_kiekis']} taškų";
										?>
                                    </a>
                                    <a class="comment badge-evt">
                                        <i class="fas fa-comment"></i>
										<?php
											echo "${row['komentaru_kiekis']} komentarų";
										?>
                                    </a>
                                </p>
                                <div class="meme-buttons">
                                    <div class="control-button">
                                        <i class="fas fa-arrow-up"></i>
										
                                    </div>
                                    <div class="control-button center-button">
                                        <i class="fas fa-arrow-down"></i>
                                    </div>
                                    <div class="control-button right-button" id="komentarai-<?php echo $id; ?>">
                                        <i class="fas fa-comment"></i>
										<script>
										document.getElementById("komentarai-<?php echo $id; ?>").onclick = function(){
											// paspaudus ant komentaro mygtuko, nukreipia i komentarus
											window.location.href = 'comments.php?postId=<?php echo $id; ?>';
										}
										</script>
                                    </div>
                                </div>
                        </div>
						<?php
							} // cia uzdarome meme_post while cikla
						?>
                </div>
                <div class="sidebar-column">
                    <div style="height: 200px; background: white;"></div>
                </div>
            </div>
			<?php
				include("includes/footer.php");
			?>
        </main>
    </div>
</body>
</html>
