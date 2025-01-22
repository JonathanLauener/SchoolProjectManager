<?php
session_start();
error_reporting(0);

require("mysql.php");

if ($_SESSION["privid"] != 2) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileToUpload"])) {
    // Get the uploaded file info
    $file = $_FILES["fileToUpload"];
    $group = $_POST["group"];
    $uploadDir = "uploads/";
    $name = $_SESSION["username"];
    

    // Validate the file
    $fileName = basename($file["name"]);
    $fileTmpName = $file["tmp_name"];
    $fileSize = $file["size"];
    $fileError = $file["error"];
    $fileType = $file["type"];
    

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // List of allowed file extensions
    $allowedExts = array("jpg", "jpeg", "png", "pdf", "docx", "txt", "odt");

    // Check if file extension is allowed
    if (in_array($fileExt, $allowedExts)) {
        // Check for upload errors
        if ($fileError === 0) {
            if ($fileSize < 5000000) { // Maximum size: 5MB
                // Create a unique name for the file
                $newFileName = "$name" . "_" . "$group" . "_" . uniqid() . "." . $fileExt;
                $fileDestination = $uploadDir . $newFileName;

                // Move the uploaded file to the uploads directory
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Insert file info into the database (Optional)
                    $username = $_SESSION["username"];
                    $sql = "INSERT INTO upload (username, groupid, path) VALUES ('$username', '$group', '$fileDestination')";
                    if ($dbh->query($sql)) {
                        echo "File uploaded successfully!";
                        $_SESSION["gotuploaded"] = 1;
                        header("location: schueler.php");
                    } else {
                        echo "Failed to save file information in database.";
                    }
                } else {
                    echo "There was an error uploading your file.";
                }
            } else {
                echo "Your file is too large. Max file size is 5MB.";
            }
        } else {
            echo "There was an error uploading your file. Error code: " . $fileError;
        }
    } else {
        echo "File type not allowed. Only " . implode(", ", $allowedExts) . " are allowed.";
    }
    
} else{
    echo "Something went wrong!";
    var_dump($_FILES);
}
?>
