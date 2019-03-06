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
			<form method='post'>
				<center>
				<div id='didelis_staciakampis'>
					<div id='staciakampis_viduje'>
						<label style="float: left">Prisijungimo vardas:</label>
						<input id='ivedimo_laukelis' name='prisijungimo_vardas' type='text' maxlength="32" required>
						<br><br>
						<label style="float: left">Slaptažodis:</label>
						<input id='ivedimo_laukelis' name='slaptazodis' type='password' maxlength="32" required>
						<br><br><br>
						<input id="prisijungti_mygtukas" type="button" name="prisijungti" type="submit" value="Prisijungti">
						</div>
				</div>
				</center>
			</form>
		
    </body>
</html>