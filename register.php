<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<form method="post" action="schueler.php">
        <input type="submit" value="Log Out" name="logout"/>
    </form>
<h3>Make a new user:</h3>
<br>
<form method="post" action="register.php">
    Username: <input type="text" name="name"><br>
    password: <input type="text" name="password"><br>
    <br>
    <input type="submit" name="submit" value="Submit">

    
    

    <?php
    require("mysql.php");
    if ($_SESSION["privid"] != 0) {
    header("Location: index.php");
    exit;
}

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST["name"];
        if (isset($_POST['password'])) {
            $hash = password_hash($_POST['password'],PASSWORD_DEFAULT);
            //echo "<p> Hash: ".$hash."</p>";
            $sql = "INSERT INTO `users`(`username`, `password`, `privid`) VALUES ('$username','$hash', 0)";
            $results = $dbh->query($sql)->fetchAll();
            header("Location: index.php");                
        }
    }
    if (isset($_POST['logout'])) {
        header("Location: index.php");
        $_SESSION["privid"] = 12;
        $_SESSION["username"] = uniqid();
    }
    echo "<h3>Every user created is an admin by default! Please change the privilege:</h3><br>";
    echo "<a href=\"geheim.php\">here</a>";
    ?>
</body>
</html>