<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post" action="schueler.php">
        <input type="submit" value="Log Out" name="logout"/>
    </form>
    
    <h1>Welcome to the student page!</h1>
    <h2>Please select your project and upload the corresponding file.</h2>
    
    <?php
        require("validate.php");
        require("mysql.php");

        if (isset($_POST['upload'])) {
            $_SESSION['group']['upload'] = $test;
            header("Location: landing.php");
        }

        if (isset($_POST['logout'])) {
            header("Location: index.php");
            $_SESSION["privid"] = 12;
            $_SESSION["username"] = uniqid();
        }

        if ($_SESSION["privid"] != 2) {
            header("Location: index.php");
            exit;
        }
        if ($_SESSION["gotuploaded"] == 1) {
            echo "<h3>Your File was uploaded successfuly</h3>";
            $_SESSION["gotuploaded"] = 0;
        }

        $name = $_SESSION["username"];
        $sql = "SELECT groupid FROM is_in WHERE username='$name'";
        $results = $dbh->query($sql)->fetchAll();
    ?>
    
    <form method="post" action="upload.php" enctype="multipart/form-data">
        <h3> choose file </h3>
        <input type="file" name="fileToUpload" id="fileToUpload"> 
        
        <h3> choose group </h3>
        <select name="group">
            <?php
                foreach ($results as $result) {
                    $group = $result["groupid"];
                    echo "<option value='$group'>$group</option>";
                }
            ?>
        </select>
        
        <h3> Submit </h3>
        <input type="submit" name="upload" value="Upload"/>
    </form>
</body>
</html>
