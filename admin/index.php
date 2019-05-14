<!DOCTYPE html>
<html>
    <head>
        <title>Memteka</title>
		<link rel="stylesheet" href="/memteka/style.css">
    </head>
    <body id='login-body'>
		<form method='post' action=>
			<div id='login-form'>
				<div id='form-inputs'>
					<input placeholder="Prisijungimo vardas" id='input' name='vartotojo_vardas' type='text' maxlength="32" required>
					<br><br>
					<input placeholder="Slapta&#382;odis" id='input' name='slaptazodis' type='password' maxlength="32" required>
					<br><br><br>
					<input id="login-button" name="prisijungti" type="submit" value="Prisijungti">
					</div>
			</div>
		</form>
    </body>
</html>

<?php
	session_start();
	require('../includes/config.php');
	$dbc=mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if(!$dbc){
		die("Negaliu prisijungti prie MySQL:".mysqli_error($dbc));
	}
	$vartotojo_vardas = "";
	$slaptazodis = "";
	
	if (isset($_POST['prisijungti'])) {
		$vartotojo_vardas = mysqli_real_escape_string($dbc, $_POST['vartotojo_vardas']);
		$slaptazodis = mysqli_real_escape_string($dbc, $_POST['slaptazodis']);

		$query = "SELECT * FROM vartotojai WHERE vartotojo_vardas='$vartotojo_vardas' and slaptazodis='$slaptazodis'";
		$result = mysqli_query($dbc, $query);

		if (mysqli_num_rows($result) == 1) { // jeigu rado viena eilute, kurios username ir slaptazodis sutampa su ivestais
			$_SESSION['vartotojo_vardas'] = $vartotojo_vardas;
			header("Location: ../index.php");
		} else {
			header("Location: index.php");
		}
}
?>
