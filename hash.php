<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Document</title>
</head>
<body>
<h1>Minimalbeispiel</h1>
<form action="hash.php" method="post">
<label>Password</label>
<input type="text" name="pass">
<input type="submit" value="Hash" name="submit1">
</form>
<?php
if (isset($_POST['submit1'])) {
$hash = password_hash($_POST['pass'],PASSWORD_DEFAULT);
echo "<p> Hash: ".$hash."</p>";
}
?>
<br>
<form action="hash.php" method="post">
<label>Password</label>
<input type="text" name="pass">
<label>Hash</label>
<input type="text" size="64" name="hash">
<input type="submit" value="Verify" name="submit2">
</form>
<?php
if (isset($_POST['submit2'])) {
if(password_verify($_POST['pass'],$_POST['hash'])) {
echo ("<h3>Passwort und hash stimmen überein.</h3>");
} else {
echo ("<h3>Passwort und hash stimmen <u>nicht</u>
überein!</h3>");
}
}
?>
<br>
<hr>
<h3>Stuktur von $_POST:</h3>
<?php
var_dump($_POST);
?>
</body>
</html>