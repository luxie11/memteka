<!DOCTYPE html>
<html>
    <head>
        <title>Memteka</title>
    	<link rel="stylesheet" href="style.css">
        <style>
			#prisijungti_mygtukas:hover {
				background-color: #e3e3e3;
			}
        </style>
    </head>
    <body>
		<form method='post' action=>
			<div id='login-form'>
				<div id='form-inputs'>
					<input id='input' name='vartotojo_vardas' placeholder="Prisijungimo vardas" type='text' maxlength="32" required>
					<input id='input' name='slaptazodis' placeholder="Slaptažodis" type='password' maxlength="32" required>
					<input id="prisijungti_mygtukas" name="prisijungti" type="submit" value="Prisijungti">
				</div>
			</div>			
		</form>
    </body>
</html>
<?php
	$dbc=mysqli_connect("localhost", "root", "", "memteka");  // Laikinas prisijungimas
	if(!$dbc){
		die("Negaliu prisijungti prie MySQL:".mysqli_error($dbc));		
	}
	$vartotojo_vardas = "";
	$slaptazodis = "";
	if (isset($_POST['prisijungti'])) {
		$vartotojo_vardas = mysqli_real_escape_string($dbc, $_POST['vartotojo_vardas']);
		$slaptazodis = mysqli_real_escape_string($dbc, $_POST['slaptazodis']);
		echo $vartotojo_vardas;
		$query = "SELECT * FROM vartotojai WHERE vartotojo_vardas='$vartotojo_vardas' and slaptazodis='$slaptazodis'";
		$result = mysqli_query($dbc, $query);
	if (mysqli_num_rows($result) == 1) { // jeigu rado viena eilute, kurios username ir slaptazodis sutampa su ivestais
		header("Location: index.html");
	} else {
		header("Location: login.php");
	}
}
?>