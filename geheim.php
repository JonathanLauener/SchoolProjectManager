<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Admin page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<form method="post" action="schueler.php">
        <input type="submit" value="Log Out" name="logout"/>
    </form>
<?php
require("validate.php");
require("mysql.php");
$spalten = [
    'username' => 'Username',
];

if ($_SESSION["privid"] != 0) {
    header("Location: index.php");
    exit;
}
if (isset($_POST['logout'])) {
    header("Location: index.php");
    $_SESSION["privid"] = 12;
    $_SESSION["username"] = uniqid();
}

$name2 = $_SESSION["username"];
//echo "Welcome to the secret site dear $name2";
//echo $_SESSION["groupid"];
$_SESSION["privid"];
?>

<br>
<h3>These are all users:</h3>
<br>
<table border=1 class="center">
    <thead>
        <tr>
        <?php
            foreach ($spalten as $spalte) {
                echo '<th>', htmlspecialchars($spalte), '</th>';
            }
            echo '<th>Privilege</th>';  // Add header for privilege
        ?>
        </tr>
    </thead>
    <tbody>
    <?php
        // Fetch all users from the database
        $sql = "SELECT * FROM users";
        $results = $dbh->query($sql);
        
        // Loop through the results and display user information
        foreach ($results as $result) {
            echo '<tr>';
            
            // Display the user details
            foreach ($spalten as $spalte => $wert) {
                echo '<td>', htmlspecialchars($result[$spalte]), '</td>';
            }
            
            // Display privilege based on privid
            $privid = $result['privid'];
            if ($privid == 0) {
                echo '<td>admin</td>';
            } elseif ($privid == 1) {
                echo '<td>lehrer</td>';
            } elseif ($privid == 2) {
                echo '<td>schueler</td>';
            } else {
                echo '<td>hacker</td>';
            }

            echo '</tr>'; // End of user row
        }
    ?>
    </tbody>
</table>

<h3>Change user privilege:</h3>
<form method="post" action="geheim.php">
    Username: <input type="text" name="changename">
    <label for="priv"></label>

    <select name="priv" id="priv">
        <option value="admin">admin</option>
        <option value="lehrer">lehrer</option>
        <option value="schüler">schüler</option>
    </select> 
    <br><br>
    <input type="submit" name="submit" value="Submit">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changename']) && isset($_POST['priv'])) {
    $name = $_POST['changename'];
    $priv = $_POST['priv'];

    // Check if the username is not empty
    if (empty($name)) {
        echo "Username cannot be empty!";
    } else {
        //print_r("[+] UPDATING USER");
        
        // Mapping the privilege names to privid values
        $privid_map = [
            'admin' => 0,
            'lehrer' => 1,
            'schüler' => 2
        ];

        if (isset($privid_map[$priv])) {
            $privid_value = $privid_map[$priv];

            // Update the user's privilege in the database
            $sql = "UPDATE users SET privid = :privid_value WHERE username = :name";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                ':privid_value' => $privid_value,
                ':name' => $name
            ]);

            echo "Privilege of user $name has been updated to $priv.";
        } else {
            echo "Invalid privilege selected.";
        }
    }
}

echo "Make new user: <a href=\"register.php\">here</a>";
echo "<br>";
echo "Make and manage projects: <a href=\"createproject.php\">here</a>";
?>

</body>
</html>