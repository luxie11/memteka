<!DOCTYPE html>
<html>
<body>

<form action="upload_meme.php" method="post" enctype="multipart/form-data">
    Pasirinkite nuotrauką, kurią norite įkelti:<br>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
	Nuotraukos pavadinimas:
	<input type="text" name="pavadinimas" id="pavadinimas" maxlength="255"><br>
    <input type="submit" value="Įkelti memą" name="submit">
</form>

</body>
</html>
