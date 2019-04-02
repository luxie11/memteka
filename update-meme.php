<?php
    require('includes/config.php');
    $dbc=mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if(!$dbc){
        die("Negaliu prisijungti prie MySQL:".mysqli_error($dbc));
    }
    $memeID = $_POST['memeID'];
    $votes = $_POST['votes'];
    $query = "UPDATE memai SET tasku_kiekis = $votes  WHERE id = $memeID";
    $result = mysqli_query($dbc, $query);
?>