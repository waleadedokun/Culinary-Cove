<?php
session_start();

include 'connect_db.php';

$is_invalid = false;

// Check if success message exists in session
if (isset($_SESSION['success_message'])) {
    echo "<p>{$_SESSION['success_message']}</p>";
    unset($_SESSION['success_message']); // Remove success message from session
}

// Check if error message exists in session
if (isset($_SESSION['error_message'])) {
    echo "<p>{$_SESSION['error_message']}</p>";
    unset($_SESSION['error_message']); // Remove error message from session
}

// fetch user's recipes from the database
function getUserRecipes($mysqli, $user_id, $searchTerm = null) {
    $recipes = [];
    $sql = "SELECT recipe_id, title, image_url FROM recipes WHERE user_id = ?";
    if ($searchTerm !== null) {
        $sql .= " AND (title LIKE ?)";
    }
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user_id);
    if ($searchTerm !== null) {
        $search = "%{$searchTerm}%";
        $stmt->bind_param("is", $user_id, $search);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    $stmt->close();
    return $recipes;
}

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Initialize variables
$userRecipes = [];

// Handle search functionality
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    // Get user's recipes with search term
    $userRecipes = getUserRecipes($mysqli, $user_id, $searchTerm);
} else {
    // Get user's recipes without search term (display all recipes)
    $userRecipes = getUserRecipes($mysqli, $user_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Culinary Cove</title>
    <style>
        .recipe-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            grid-gap: 20px;
        }
        .recipe-card {
            border: 1px solid #ccc;
            padding: 10px;
        }
        .recipe-card img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h2>My Recipes</h2>
    <h3><a href="logout.php">Logout</a></h3>
    <h3><a href="recipes.php">View all Recipes</a></h3>

    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search your recipes">
        <button type="submit">Search</button>
    </form>

    <h2><a href="add_recipe.html">Add Recipe</a></h2>

    <div class="recipe-container">
        <?php foreach ($userRecipes as $recipe): ?>
            <div class="recipe-card">
                <img src="<?= $recipe['image_url'] ?>" alt="<?= $recipe['title'] ?>">
                <h3><?= $recipe['title'] ?></h3>
                <a href="view_recipe.php?recipe_id=<?= $recipe['recipe_id'] ?>">View</a>
                <a href="edit.php?recipe_id=<?= $recipe['recipe_id'] ?>">Edit</a>
                <a href="delete.php?recipe_id=<?= $recipe['recipe_id'] ?>" onclick="return confirm('Are you sure you want to delete this recipe?')">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
