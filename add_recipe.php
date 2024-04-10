<?php

session_start();

$mysqli = require 'connect_db.php';

// Check if user is logged in 
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "User is not logged in.";
    header("Location: login-signup.php");
    exit();
}

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $title = $_POST['title'];
    $description = $_POST['description'];
    $instructions = $_POST['instructions'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    
    // File upload handling
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        $_SESSION['error_message'] = "File is not an image.";
        header("Location: add_recipe.php");
        exit();
    }
    
    // Allow only certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $_SESSION['error_message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        header("Location: add_recipe.php");
        exit();
    }
    
    // Check if file already exists
    if (file_exists($target_file)) {
        $_SESSION['error_message'] = "Sorry, file already exists.";
        header("Location: add_recipe.php");
        exit();
    }
    
    // Move uploaded file to target directory
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $_SESSION['error_message'] = "Sorry, there was an error uploading your file.";
        header("Location: add_recipe.html");
        exit();
    }
    
    // Prepare SQL statement to insert recipe into database
    $sql = "INSERT INTO recipes (user_id, title, description, instructions, category, location, image_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare and bind parameters
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("issssss", $user_id, $title, $description, $instructions, $category, $location, $target_file);
    
    // Execute the SQL statement
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Recipe added successfully.";
        header("Location: cook_home.php");
        exit(); 
    } else {
        $_SESSION['error_message'] = "Error updating recipe: " . $stmt->error;
        header("Location: add_recipe.html");
        exit();
    }

    $stmt->close();
    $mysqli->close();
}
?>
