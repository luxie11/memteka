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
			session_start();
			ob_start();
			
			if(!isset($_GET['postId'])) {  // jeigu zmogus ateina i comments.php be GET parametro ?postId=x
				header('Location: index.php');
			}
			
			include("includes/header.php");
			
			require('includes/config.php');
			$dbc=mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
			if(!$dbc){
				die("Negaliu prisijungti prie MySQL:".mysqli_error($dbc));
			}
			
			$postId = htmlentities($_GET['postId']);
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
								<a><?php echo htmlentities($row['pavadinimas']); ?></a>
							</li>
							<?php
								} // cia uzdarome category-item while cikla
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
                                <?php
									echo htmlentities($row['pavadinimas']);
								?>
								<a href="edit.php?memeId=<?php echo $postId; ?>" id='edit_meme_button'><i class="fas fa-pen"></i></a>
								<a href="delete_meme.php?memeId=<?php echo $postId; ?>" id='delete_meme_button' onclick="return confirm('Ar tikrai norite ðalinti memà ir visus jo komentarus? Duomenys bus iðtrinti negráþtamai!')"><i class="fas fa-trash"></i></a>
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
						<div class="meme-buttons">
							<div class="control-button">
								<i class="fas fa-arrow-up"></i>
							</div>
							<div class="control-button center-button">
								<i class="fas fa-arrow-down"></i>
							</div>
							<div class="control-button right-button" onclick="google.com">
								<i class="fas fa-comment"></i>
							</div>
						</div>
						<hr>
						<div class="meme-comments">
							<div class="comment-box-first">
								<div class="payload">
									<form method='post'>
										<textarea placeholder="J&#363s&#371 vardas" name="vardas" maxlength="32" class="comment-username" required></textarea>
										<textarea placeholder="J&#363s&#371 komentaras" name="komentaras" maxlength="255" class="comment-text-area" required></textarea>
										<div class="action">
											<input type="submit" class="comment-post-btn" name="submit" value="Ra&#353yti">
										</div>
									</form>
								</div>
							</div>
							<?php
								$query = "SELECT * FROM komentarai WHERE fk_memo_id=$postId ORDER BY data DESC";
								$result = mysqli_query($dbc, $query);
								while($row=mysqli_fetch_assoc($result))
								{
									date_default_timezone_set('Europe/Vilnius');
									$data = date('Y-m-d H:i', strtotime($row['data']));// pakeiciam datos formata, kad nerodytu sekundziu
							?>
							
							<div class="comment-entry">
								<div class="payload">
									<p class="username-date">
										<span class="username"><?php echo htmlentities($row['vardas']); ?></span>
										
										<span class="date"><?php echo htmlentities($data); ?></span>
										<a href="delete_comment.php?commentId=<?php echo $row['id']; ?>&&postId=<?php echo $postId; ?>" id='delete_meme_button' onclick="return confirm('Ar tikrai norite &#353alinti &#353&#303 komentar&#261;?')"><i class="fas fa-trash"></i></a>
									</p>
									<div class="comment-content">
										<?php echo htmlentities($row['komentaras']); ?>
									</div>
								</div>
							</div>
							
							<?php
								} // cia uzdarome category-item while cikla
							?>
							
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

<?php
	if (isset($_POST['submit'])) {
		$errors = 0; 
		$vardas = mysqli_real_escape_string($dbc, $_POST['vardas']);
		//$_SESSION['create_course_pavadinimas'] = $pavadinimas;				// kad nedingtu ivestas pavadinimas
		$komentaras = mysqli_real_escape_string($dbc, $_POST['komentaras']);
		//$_SESSION['create_course_aprasymas'] = $aprasymas;					// kad nedingtu ivestas aprasymas
  
		if (empty($vardas)) {
			//$_SESSION['error_create_course_pavadinimas'] = "Áveskite pavadinimà";
			$errors++;
		}
		
		if (empty($komentaras)) {
			//$_SESSION['error_create_course_aprasymas'] = "Áveskite apraðymà";
			$errors++;
		}
		
		if ($errors == 0) {
			date_default_timezone_set('Europe/Vilnius');
			$data = date('Y-m-d H:i', time());
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$query = "INSERT INTO komentarai (vardas, komentaras, ip, data, fk_memo_id)
					  VALUES ('$vardas', '$komentaras', '$ip', '$data', '$postId'); UPDATE memai SET komentaru_kiekis=komentaru_kiekis+1 WHERE id='$postId';";
					  
			if(mysqli_multi_query($dbc, $query))
			{
			//	unset($_SESSION['create_course_pavadinimas']);	// isvalome is sesijos
			//	unset($_SESSION['create_course_aprasymas']);
			//	unset($_SESSION['create_course_date']);
			//	unset($_SESSION['create_course_time']);
			//	unset($_SESSION['create_course_kaina']);
			//	unset($_SESSION['create_course_vietu_skaicius']);
				
			//	$message = "Kursas sukurtas sëkmingai!";
			//	echo "<script type='text/javascript'>alert('$message');location='index.php';</script>"; // issoka alert, po to nukreipia atgal i index.php
				header("Location: comments.php?postId=$postId");
			} else {
				header("Location: index.php");
			}
		} else {
				//$_SESSION['error_create_course_vietu_skaicius'] = "Sukurti kurso nepavyko. Bandykite dar kartà";
				//header("Location: index.php");
		}
	}
?>