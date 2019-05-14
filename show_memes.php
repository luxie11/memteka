<?php
    session_start();
    if(!isset($dbc)){
        require('includes/config.php');
        $dbc = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
		if(!$dbc){
			die("Negaliu prisijungti prie MySQL:".mysqli_error($dbc));
        }
    }

    if(isset($_POST['memeCount'])){
        $memeCount = $_POST['memeCount'];
    } else{
        $memeCount = 1;
    }
    $linkCategory = $_POST['categories'];
    

    if (strcmp($linkCategory, 'index.php') == 0 || strlen($linkCategory) == 0) {
        $category = "";
        $stmt_category = $dbc->prepare("SELECT COUNT(fk_kategorijos_pavadinimas) AS kiekis FROM memai_kategorijos");
        $stmt = $dbc->prepare("SELECT * FROM memai, memai_kategorijos  GROUP BY memai.id LIMIT $memeCount, 1");
    } else {
        $category = $linkCategory;
        $stmt_category = $dbc->prepare("SELECT COUNT(fk_kategorijos_pavadinimas) AS kiekis FROM memai_kategorijos WHERE fk_kategorijos_pavadinimas = ?");
        $stmt_category -> bind_param('s', $category);
        $stmt = $dbc->prepare("SELECT * FROM memai, memai_kategorijos WHERE id = fk_memo_id AND fk_kategorijos_pavadinimas = ? LIMIT $memeCount, 1");
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
                <?php 
                    } 
                ?>
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