<?php
session_start();
include 'connect_db.php';


// Check if recipe_id is provided
if (!isset($_GET['recipe_id'])) {
    $_SESSION['error_message'] = "Recipe ID is not provided.";
    header("Location: cook_home.php");
    exit();
}


$user_id = $_SESSION['user_id'];
$recipe_id = $_GET['recipe_id'];

// Prepare SQL statement to delete recipe from database
$sql = "DELETE FROM recipes WHERE recipe_id = ? AND user_id = ?";


$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $recipe_id, $user_id);


if ($stmt->execute()) {
    // Set success message in session
    $_SESSION['success_message'] = "Recipe deleted successfully.";
} else {
    $_SESSION['error_message'] = "Error deleting recipe: " . $stmt->error;
}

$stmt->close();
$mysqli->close();

header("Location: cook_home.php");
exit();
?>
