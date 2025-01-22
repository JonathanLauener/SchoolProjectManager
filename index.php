<?php
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
        <h3>Please log-in:</h3>
        <?php
            require("mysql.php");




            if (isset($_POST['logout'])) {
                header("Location: index.php");
                $_SESSION["privid"] = 12;
                $_SESSION["username"] = uniqid();
                $_SESSION["loggedin"] = 0;
            }


            // Spaltennamen festlegen
            $spalten = [
                'username' => 'Username',
                'password' => 'Password',
            ];
        ?>
        
        <!-- TABELLE für die anzuzeigenden Daten erstellen  
        <table border=1 class="center">
            <thead>
                <tr>
                <?php
                    foreach ($spalten as $spalte) {
                        echo '<th>', htmlspecialchars($spalte), '</th>';
                }
                ?>
                </tr>
            </thead>
        <tbody>
        <?php
            $sql ="SELECT * FROM users";
            $results = $dbh->query($sql);
            // äußere Schleife erzeugt Zeilen...
            foreach($results as $result) {
                echo '<tr>';
                // innere Schleife erzeugt Zellen
                foreach ($spalten as $spalte => $wert) {
                    echo '<td>',htmlspecialchars($result[$spalte]),'</td>';
                }
                echo '</tr>'; // Ende einer Zeile
            }			
        ?>
        <?php
        //$username = $dbh->query("SELECT `username` FROM `credentials`");
        //$password = $dbh->query("SELECT `password` FROM `credentials`");
        //print_r($username->fetchAll());
        //print_r($password->fetchAll());
        //print_r("Hallo, $result[0]! Ist deine Email noch aktuell? <br> Wir haben die hier gespeichert: $result[2]");
        
        //if($result[1] == '12345678'){
        //    print_r("Das passwort ist richtig");
        //} else {print_r("Das passwort ist falsch");}
        ?>
        
        <tbody>
    <table>
    -->
    <!-- ENDE DER TABELLE -->
    <?php
        //print_r($dbh->query($sql)->fetchAll());
        //print_r($tralse);
        
    ?>
    <br>
    <br>
    <form method="post" action="validate.php">
    Username: <input type="text" name="name"><br>
    password: <input type="text" name="password"><br>
    <br>
    <input type="submit" name="submit" value="Submit">
</form>
    <br>
    
    </body>
</html>