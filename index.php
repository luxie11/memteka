<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Memteka</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/lt_LT/sdk.js#xfbml=1&version=v3.2&appId=803435083363148&autoLogAppEvents=1"></script> <!-- REIKIA FB !!!!!!!!!!!!!!!!! -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
                                $categoriesArray = array();
								while($row=mysqli_fetch_assoc($result))
								{			
                                    array_push($categoriesArray, $row['pavadinimas']); 				
							?>
							<li class="category-item">
								<a><?php echo htmlentities($row['pavadinimas']); ?></a>
								<?php
									if(isset($_SESSION['vartotojo_vardas'])) { // jeigu admin, rodyti edit ir delete mygtukus
								?>
									<a href="delete_category.php?category=<?php echo $row['pavadinimas']; ?>" id='delete_meme_button' onclick="return confirm('Ar tikrai norite šalinti kategoriją? Duomenys bus ištrinti negrįžtamai!')"><i class="fas fa-trash"></i></a>
								<?php } ?>
							</li>
							<?php
								} // cia uzdarome category-item while cikla
							?>
                        </ul>
                    </aside>
                </div>
                <div class="main-column">
				<?php
                   if(!isset($dbc)){
                        require('includes/config.php');
                        $dbc = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                        if(!$dbc){
                            die("Negaliu prisijungti prie MySQL:".mysqli_error($dbc));
                        }
                    }

                    if (!isset($_GET['category'])) {
                        $category = "";
                        $stmt_category = $dbc->prepare("SELECT COUNT(fk_kategorijos_pavadinimas) AS kiekis FROM memai_kategorijos");
                        $stmt = $dbc->prepare("SELECT * FROM memai, memai_kategorijos  GROUP BY memai.id LIMIT 2");
                    } else {
                        $category = $_GET['category'];
                        $stmt_category = $dbc->prepare("SELECT COUNT(fk_kategorijos_pavadinimas) AS kiekis FROM memai_kategorijos WHERE fk_kategorijos_pavadinimas = ?");
                        $stmt_category -> bind_param('s', $category);
                        $stmt = $dbc->prepare("SELECT * FROM memai, memai_kategorijos WHERE id = fk_memo_id AND fk_kategorijos_pavadinimas = ? LIMIT 2");
                        $stmt -> bind_param('s', $category);
                    }						
                    if($stmt_category && $stmt_category -> execute() && $stmt_category -> store_result() && $stmt_category -> bind_result($kiekis)) {
                        $stmt_category -> fetch();
                        if ($kiekis != 0) {
                            if($stmt && $stmt -> execute() && $stmt -> store_result() && $stmt -> bind_result($id, $pavadinimas, $nuoroda, $tasku_kiekis, $komentaru_kiekis, $data, $fk_vartotojo_vardas, $fk_kategorijos_pavadinimas, $fk_memo_id)) {
                                while ($stmt -> fetch()) {
                ?>
                    <div class="meme-post" data-meme-post=<?php echo $id; ?>>
                        <div class="meme-content">
                            <h3 class="meme-title">
                                <?php
                                    echo htmlentities($pavadinimas);
                                    if(isset($_SESSION['vartotojo_vardas'])) { // jeigu admin, rodyti edit ir delete mygtukus
                                ?>
                                <a href="edit.php?postId=<?php echo $id; ?>" id='edit_meme_button'><i class="fas fa-pen"></i></a>
                                <a href="delete_meme.php?postId=<?php echo $id; ?>" id='delete_meme_button' onclick="return confirm('Ar tikrai norite šalinti memą ir visus jo komentarus? Duomenys bus ištrinti negrįžtamai!')"><i class="fas fa-trash"></i></a>
                                <?php } ?>
                                </h3>
                        </div>
                        <div class="meme-image">
                            <img src="<?php echo htmlentities($nuoroda);?>" alt="<?php echo htmlentities($pavadinimas);?>">
                        </div>
                        <p class="post-meta">
                            <a class="point badge-evt">
                            <img src="images/arrows.png" alt="Upvotes" style="width:14px;height:14px;">
                                <?php
                                    echo htmlentities($tasku_kiekis), ' taškų';
                                ?>
                            </a>
                            <a class="comment badge-evt">
                                <i class="fas fa-comment"></i>
                                <?php
                                    echo htmlentities($komentaru_kiekis), ' komentarų';
                                ?>
                            </a>
                        </p>
                        <div class="meme-buttons">
                            <div class="control-button voting upvote" data-upvote-id=<?php echo $id; ?>>
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="control-button voting center-button downvote" data-downvote-id=<?php echo $id; ?>>
                                <i class="fas fa-arrow-down"></i>
                            </div>
                            <div class="control-button right-button" id="komentarai-<?php echo $id; ?>">
                                <i class="fas fa-comment"></i>
                            <script>
                                document.getElementById("komentarai-<?php echo $id; ?>").addEventListener('click', function(){
                                    window.open('comments.php?postId=<?php echo $id; ?>','_blank');
                                })
                            </script>
                            </div>
                        </div>
                    </div>
                    <?php
                                    }	// uzdarome memu while cikla
                                $stmt->close();
                                }
                            } else { // jeigu kiekis = 0
                                header('Location: index.php');
                            }
                        $stmt_category->close();                            
                    }
                    ?>
                </div>
                <div class="sidebar-column">
                <div class="fb-page" data-href="https://www.facebook.com/memtekalt" data-tabs="timeline" data-width="500" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/memtekalt" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/memtekalt">Memteka</a></blockquote></div>
                </div>
            </div>
			<?php
				include("includes/footer.php");
			?>
        </main>
    </div>
    <script>
    var categoriesArray = <?php echo json_encode($categoriesArray); ?>
    </script>
    <script src="js/memteka.js"></script>
    <script src="js/loading.js"></script>
</body>
</html>
