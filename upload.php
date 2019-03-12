<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Memeteka</title>
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
        <?php include("includes/header.php"); ?>
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
                            $query = 'SELECT * 
                                      FROM kategorijos';
                            $result = mysqli_query($dbc, $query);
                            while($row=mysqli_fetch_assoc($result)){
                                echo "<li class=\"category-item\">
								        <a>" .$row['pavadinimas']. "</a>
							          </li>";			
                             } 
                        ?>
                        </ul>
                    </aside>
                </div>
                <div class="main-column">
                    <div class="meme-upload">
                        <form action="upload_meme.php" method="post" enctype="multipart/form-data">
                            Pasirinkite nuotrauką, kurią norite įkelti:<br>
                            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/png, image/jpeg, image/jpg"><br>
                            Nuotraukos pavadinimas:
                            <input type="text" name="pavadinimas" id="pavadinimas" maxlength="255"><br>
                            <input type="submit" value="Įkelti memą" name="submit">
                        </form>
                    </div>
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
