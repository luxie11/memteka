<!Doctype html>
<html>
    <head>
        <title>Memteka</title>
        <style>
			body {
				background-color: #e0e0e0;
				font-family: sans-serif;
			}

			#staciakampis_viduje {
				height: 100px;
				width: 305px;
			}

			#mygtukas {
				border-radius: 10px;
				background-color: #f6892b;
				border-color: #000000;
			}

			#didelis_staciakampis {
				height: 150px;
				width: 500px;
				margin-top:300px;
				padding-top:50px;
				background-color: #f0f0f0;
				border-radius: 10px;
			}
			#prisijungti_mygtukas {
				height: 30px;
				width: 305px;
				background-color: #f123as;
				border-radius: 10px;
				border: 1px solid #f6892b;
			}

			#prisijungti_mygtukas:hover {
				background-color: #e3e3e3;
			}

			#ivedimo_laukelis {
				float:right;
				width:150px;
				border-radius:5px;
				border: none;
			}
        </style>
    </head>
    <body>
		<form method='post' action=>
			<center>
			<div id='didelis_staciakampis'>
				<div id='staciakampis_viduje'>
					<label style="float: left">Prisijungimo vardas:</label>
					<input id='ivedimo_laukelis' name='vartotojo_vardas' type='text' maxlength="32" required>
					<br><br>
					<label style="float: left">Slapta&#382;odis:</label>
					<input id='ivedimo_laukelis' name='slaptazodis' type='password' maxlength="32" required>
					<br><br><br>
					<input id="prisijungti_mygtukas" name="prisijungti" type="submit" value="Prisijungti">
					</div>
			</div>
			</center>
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
			echo $_SESSION['vartotojo_vardas'];
			//header("Location: ../index.php");
		
		} else {
			header("Location: asdads.php");
		}
}
?>