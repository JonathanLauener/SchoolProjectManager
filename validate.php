<?php
session_start();
error_reporting(0);

?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <title>Titel der Seite | Name der Website</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
<?php
require("mysql.php");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate name
    //$name = test_input($_POST["name"]);
    if (isset($_POST['name']) && isset($_POST['password'])) {
    $nameErr = "";
    //if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
    //    $nameErr = "Only letters and white space allowed";
    //}
    
    if (isset($_POST['password'])) {
        $hash = password_hash($_POST['password'],PASSWORD_DEFAULT);
        echo "<p> Hash: ".$hash."</p>";
        }

    // Sanitize and validate email
    //$password = test_input($_POST["password"]);
    $password = $_POST["password"];
    // ... Additional validation checks ...
    $name = $_POST["name"];
    $sql ="SELECT * FROM users where username='$name'";
    //echo "This is the query: $sql";
    //$results = $dbh->query($sql);
    $results = $dbh->query($sql)->fetchAll();
    $pass = $results[0]["password"];
    
    //print_r($results);  
    //echo "\n $pass \n";
    //echo "$password";

    if(password_verify($password,$pass)) {
        //print_r("This is your password: $password");
        //echo "\n Hash stimmt überein";
        //$sql ="SELECT groupid FROM users where username='$name'";
        //$results = $dbh->query($sql)->fetchAll();
        //$groupid = $results[0]["groupid"];
        
        $sql ="SELECT privid FROM users where username='$name'";
        $results = $dbh->query($sql)->fetchAll();
        $privid = $results[0]["privid"];

        $_SESSION["username"] = $name;
        $_SESSION["privid"] = $privid;
        //$_SESSION["groupid"] = $groupid;
        $name1 = $_SESSION["username"];

        if ($privid == 0) {
            header("Location: geheim.php");    
        } elseif ($privid == 1){
            header("Location: lehrer.php");
        } elseif ($privid == 2) {
            header("Location: schueler.php");
        } else {
            header("Location: index.php");
        }
        
        echo "Password is correct!";
        echo "\n $name1";
        
    } 
    else{print_r("Your password is incorrect!");} 
    //print_r("This is your password: $password");
    //var_dump($_POST);
}
    

}
?>
</body>