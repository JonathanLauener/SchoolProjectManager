<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post" action="lehrer.php">
        <input type="submit" value="Log Out" name="logout"/>
    </form>
    
    <h1>Welcome to the teacher page!</h1>

    <?php
    if (isset($_SESSION["username"])) {
        $name = $_SESSION["username"];
    }
    
    require("mysql.php");

    //Check if you are a teacher 
    if ($_SESSION["privid"] != 1) {
      header("Location: index.php");
      exit;
    }

    // If logout is requested, handle it
    if (isset($_POST['logout'])) {
        header("Location: index.php");
        $_SESSION["privid"] = 12;
        $_SESSION["username"] = uniqid();
        exit(); // Stop further execution after redirect
    }

    // Define the columns to display in the table
    $spalten = [
        'member' => 'Schueler',
        'path' => 'projekt',
    ];

    // Check if a group has been selected
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['group'])) {
        // Fetch the selected group
        $selectedGroup = $_POST['group'];

        // Fetch details of the selected group (only one group, since we're filtering by $selectedGroup)
        $sql = "SELECT groupid FROM groups WHERE owner='$name' AND groupid='$selectedGroup'";
        $results = $dbh->query($sql)->fetchAll();

        // If the selected group exists, display it
        if (count($results) > 0) {
            $groupnumber = htmlspecialchars($results[0]["groupid"]);
            echo "<h3>Group $groupnumber:</h3>";  // Title for the selected group
            echo "<table border=1 class='center'>";
            echo "<thead>";
            echo "<tr>";
            foreach ($spalten as $spalte) {
                echo '<th>', htmlspecialchars($spalte), '</th>';
            }
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            // Fetch the members and their uploaded paths for the selected group
            $sql = "SELECT username, path FROM upload WHERE groupid=$groupnumber";
            $groupresult = $dbh->query($sql)->fetchAll();

            foreach ($groupresult as $groupdata) {
                echo "<tr>";
                $groupmembername = htmlspecialchars($groupdata["username"]);
                echo "<td>$groupmembername</td>";
                $grouppath = htmlspecialchars($groupdata["path"]);
                echo "<td><a href=\"$grouppath\">$grouppath</a></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No data found for the selected group.</p>";
        }
    } else {
        echo "<p>Please select a group!</p>";
    }
    ?>

    <!-- Group selection dropdown -->
    <form method="post" action="lehrer.php">
        <h3>Select Group:</h3>
        <select name="group">
            <?php
            // Fetch all groups owned by the user and populate the dropdown
            $sql = "SELECT groupid FROM groups WHERE owner='$name'";
            $groupResults = $dbh->query($sql)->fetchAll();

            // Loop through each group and create an <option> element
            if (count($groupResults) > 0) {
                foreach ($groupResults as $group) {
                    $groupnumber = htmlspecialchars($group['groupid']);
                    echo "<option value=\"$groupnumber\">Group $groupnumber</option>";
                }
            } else {
                echo "<option value=\"\">No groups available</option>";
            }
            ?>
        </select>
        <input type="submit" name="groupview" value="Bestätigen"/>
    </form>

    <h3>Manage your groups:</h3>
    <a href="manageprojects.php"> here </a>
</body>
</html>
