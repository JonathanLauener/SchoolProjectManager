<?php
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    try {
        $dsn = 'mysql:host=10.16.1.108;dbname=jonathan;charset=utf8';
        $username = 'jonathan';
        $password = 'lGRdfYrCQFJPEZSE';
        //$dbh = new PDO($dsn);
        $dbh = new PDO($dsn, $username, $password) ;
    } catch(Exception $e) {
        die('Interner Fehler: Die Datenbank-Verbindung konnte 
            nicht aufgebaut werden.'. $e ->getMessage());
    }
    ?>
</body>
</html>