<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Create a project</title>
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
    'member' => 'Username',
    'owner' => 'Owner',
    'groupid' => 'Group ID'
];

if (!isset($_SESSION["privid"])) {
    header("Location: index.php");
}


if ($_SESSION["privid"] != 0 && $_SESSION["privid"] != 1) {
    header("Location: index.php");
    exit;
}

$name2 = $_SESSION["username"];
//echo "Welcome to the secret site dear $name2";
//echo $_SESSION["groupid"];
$_SESSION["privid"];
?>

<br>
<br>

    <?php
        
            $name = $_SESSION["username"];
        
        
        require("mysql.php");
    
        // If logout is requested, handle it
        if (isset($_POST['logout'])) {
            header("Location: index.php");
            $_SESSION["privid"] = 12;
            $_SESSION["username"] = uniqid();
            exit(); // Stop further execution after redirect
        }
    
        // Define the columns to display in the table
        $spalten = [
            'username' => 'Username',
        ];
    
        // Check if a group has been selected
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['group'])) {
            // Fetch the selected group
            $selectedGroup = $_POST['group'];
    
            // Fetch details of the selected group (only one group, since we're filtering by $selectedGroup)
            $sql = "SELECT username FROM is_in where groupid='$selectedGroup'";
            $results = $dbh->query($sql)->fetchAll();
    
            // If the selected group exists, display it
            if (count($results) > 0) {
                echo "<h3>Group $selectedGroup:</h3>";  // Title for the selected group
                echo "<table border=1 class='center'>";
                echo "<thead>";
                echo "<tr>";
                foreach ($spalten as $spalte) {
                    echo '<th>', htmlspecialchars($spalte), '</th>';
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
    
                foreach ($results as $groupdata) {
                    echo "<tr>";
                    $groupmembername = htmlspecialchars($groupdata["username"]);
                    echo "<td>$groupmembername</td>";
                    
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>Please add some members to this group.</p>";
            }
        } else {
            echo "<p>Please select a group to view!</p>";
        }



        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['changename'])) {

                $user = $_POST['changename'];
                if (!isset($_POST['groupadd'])) {
                    echo "Please select a group to add the User to!";
                }else {
                $changeGroup = $_POST["groupadd"];

                $sql = "SELECT username FROM users WHERE username='$user'";
                $results = $dbh->query($sql)->fetchAll();
                $sql = "SELECT username FROM is_in WHERE username='$user' AND groupid='$changeGroup'";
                $results1 = $dbh->query($sql)->fetchAll();
                if (count($results)>0) {
                    if (count($results1)>0) {
                        echo "<h3>The user $user is already in group $changeGroup</h3>";
                    }else {
                        $sql = "INSERT INTO `is_in`(`username`, `groupid`) VALUES ('$user','$changeGroup')";
                    $results = $dbh->query($sql)->fetchAll();
                    echo "<h3> User $user added to Group $changeGroup </h3>";
                    }
                

                
                } else{
                    echo "<h3>Please select a real User to add!</h3>";
                }
                
   
                }
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['removename'])) {
            $user = $_POST['removename'];
                if (!isset($_POST['groupremove'])) {
                    echo "Please select a group to remove the user from!";
                }else {
                $changeGroup = $_POST["groupremove"];

                $sql = "SELECT username FROM users WHERE username='$user'";
                $results = $dbh->query($sql)->fetchAll();
                $sql = "SELECT username FROM is_in WHERE username='$user' AND groupid='$changeGroup'";
                $results1 = $dbh->query($sql)->fetchAll();
                if (count($results)>0) {
                    if (!count($results1)>0) {
                        echo "<h3>The user $user is not in group $changeGroup</h3>";
                    }else {
                        $sql = "DELETE FROM is_in WHERE username='$user' AND groupid='$changeGroup'";
                        $results = $dbh->query($sql)->fetchAll();
                        echo "<h3>Deleted user $user from group $changeGroup!</h3>";
                    }
                

                
                } else{
                    echo "<h3>Please select a real User to delete!</h3>";
                }
                
   
                }
        }

    ?>

<form method="post" action="manageprojects.php">
        <h3>Select group:</h3>
        <select name="group">
            <?php
            // Fetch all groups owned by the user and populate the dropdown
            $sql = "SELECT groupid FROM groups WHERE owner='$name2'";
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





<br>
<h3>These are all of the users:</h3>
<br>



<table border=1 class="center">
            <thead>
                <tr>
                <?php
                
            $spaltenuser = [
                'username' => 'Username',
                    ];
                    foreach ($spaltenuser as $spalteuser) {
                        echo '<th>', htmlspecialchars($spalteuser), '</th>';
                }
                ?>
                </tr>
            </thead>
        <tbody>
<?php

            $sql ="SELECT * FROM users";
            $resultsuser = $dbh->query($sql);
            // äußere Schleife erzeugt Zeilen...
            foreach($resultsuser as $resultuser) {
                echo '<tr>';
                // innere Schleife erzeugt Zellen
                foreach ($spaltenuser as $spalteuser => $wert) {
                    echo '<td>',htmlspecialchars($resultuser[$spalteuser]),'</td>';
                }
                echo '</tr>'; // Ende einer Zeile
            }			
        ?>
        <table>


            
<h3>Add user to group:</h3>
<form method="post" action="manageprojects.php">
    Username: <input type="text" name="changename">
    <label for="groupadd"></label>

    <select name="groupadd" id="groupadd">
    <?php
            // Fetch all groups owned by the user and populate the dropdown
            $sql = "SELECT groupid FROM groups WHERE owner='$name2'";
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
    <br><br>
    <input type="submit" name="submit" value="Submit">
</form>


<h3>Remove user from group:</h3>
<form method="post" action="manageprojects.php">
    Username: <input type="text" name="removename">
    <label for="groupremove"></label>

    <select name="groupremove" id="groupremove">
    <?php
            // Fetch all groups owned by the user and populate the dropdown
            $sql = "SELECT groupid FROM groups WHERE owner='$name2'";
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
    <br><br>
    <input type="submit" name="submit" value="Submit">
</form>

</body>
</html>