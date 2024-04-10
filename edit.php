<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect_db.php';

$is_invalid = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather form data
$recipe_id = $_POST['recipe_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$instructions = $_POST['instructions'];
$category = $_POST['category']; 
$location = $_POST['location'];


    // Check if a new image file is uploaded
    if ($_FILES["image"]["size"] > 0) {
        // File upload handling code
        // Add code to move the uploaded file to the desired directory and update the image URL in the database
        $target_dir = "assets/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            exit();
        }

        // Allow only certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            exit();
        }

        // Move uploaded file to target directory
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }

        // Update the image URL in the database
        $image_url = $target_file;
        $update_image_sql = "UPDATE recipes SET image_url = ? WHERE recipe_id = ?";
        $update_image_stmt = $mysqli->prepare($update_image_sql);
        $update_image_stmt->bind_param("si", $image_url, $recipe_id);
        if (!$update_image_stmt->execute()) {
            echo "Error updating image URL: " . $update_image_stmt->error;
            exit();
        }
    }

   // Prepare SQL statement to update recipe in database
$update_recipe_sql = "UPDATE recipes 
SET title = ?, description = ?, instructions = ?, category = ?, location = ?
WHERE recipe_id = ?";

// Prepare and bind parameters
$stmt = $mysqli->prepare($update_recipe_sql);
$stmt->bind_param("sssisi", $title, $description, $instructions, $category, $location, $recipe_id);



if ($stmt->execute()) {
   
    $_SESSION['success_message'] = "Recipe updated successfully.";
   
    header("Location: cook_home.php");
    exit();
} else {
    // Set error message in session
    $_SESSION['error_message'] = "Error updating recipe: " . $stmt->error;
   
    header("Location: cook_home.php");
    exit(); 
}

    $stmt->close();
    $mysqli->close();
} else {
    // Retrieve recipe details from database
    if (isset($_GET['recipe_id'])) {
        $recipe_id = $_GET['recipe_id'];
        $select_recipe_sql = "SELECT * FROM recipes WHERE recipe_id = ?";
        $select_recipe_stmt = $mysqli->prepare($select_recipe_sql);
        $select_recipe_stmt->bind_param("i", $recipe_id);
        $select_recipe_stmt->execute();
        $result = $select_recipe_stmt->get_result();
        $recipe = $result->fetch_assoc();
        $select_recipe_stmt->close();
    } else {
        echo "Recipe ID not provided.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
</head>
<body>
    <h2>Edit Recipe</h2>

    <?php
    // Display success message if it exists
    if (isset($_SESSION['success_message'])) {
        echo "<p>{$_SESSION['success_message']}</p>";
        // Remove the success message from session to prevent it from displaying again
        unset($_SESSION['success_message']);
    }
    ?>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="recipe_id" value="<?php echo $recipe['recipe_id']; ?>">
        
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo $recipe['title']; ?>" required><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" required><?php echo $recipe['description']; ?></textarea><br>

        <label for="instructions">Instructions:</label><br>
        <textarea id="instructions" name="instructions" rows="4" required><?php echo $recipe['instructions']; ?></textarea><br>

        <label for="category">Category:</label><br />
      <select name="category">
        <option value="">Select category</option>
        <option value="Breakfast">Breakfast Meal</option>
        <option value="Lunch">Lunch Meal</option>
        <option value="Supper">Supper Meal</option>
      </select><br />

      <label for="location">Location:</label><br />
      <select name="location">
        <option value="">Select location</option>
        <option value="Nigeria">Nigeria</option>
        <option value="United Kingdom">United Kingdom</option>
      </select><br />



        <label for="image">Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*"><br>

        <input type="submit" value="Save">
    </form>
</body>
</html>



