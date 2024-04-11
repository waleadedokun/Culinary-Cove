<?php
$mysqli = require 'connect_db.php';


function getAllRecipes($mysqli) {
    $recipes = [];
    $sql = "SELECT r.recipe_id, r.title, r.image_url, u.username FROM recipes r JOIN users u ON r.user_id = u.user_id";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    return $recipes;
}

// Initialize variables
$allRecipes = [];
$searchTerm = '';
$category = '';
$location = '';

// Check if search parameters are present in the URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $location = isset($_GET['location']) ? $_GET['location'] : '';

    // Construct the search query based on search term, category, and location
    $sql = "SELECT r.recipe_id, r.title, r.image_url, u.username 
            FROM recipes r 
            JOIN users u ON r.user_id = u.user_id 
            WHERE r.title LIKE '%$searchTerm%'";

    if (!empty($category)) {
        $sql .= " AND r.category = '$category'";
    }

    if (!empty($location)) {
        $sql .= " AND r.location = '$location'";
    }

    // Execute the search query
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        $allRecipes[] = $row;
    }
} else {
    // If no search parameters are present, get all recipes
    $allRecipes = getAllRecipes($mysqli);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" 
    integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
      integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="shortcut icon" href="./icons/cutleries.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CULINARY-COVE</title>
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
        .r-container{
            align-content="center"
            padding-left: 20px;
        }
    </style>
</head>
<body>
     <!--Header (Navbar) Begins Here-->
     <header>
        <div class="header">

    <!-- Navbar Activation Toogle Begins Here-->
           <div class="headerbar">
            <div class="account">
                <ul>
                    <a href="">
                        <li>
                            <i class="bi bi-house-fill"></i>
                        </li>
                    </a>
                    <a href="#">
                        <li>
                            <i class="bi bi-search" id="searchicon1 "></i>
                        </li>
                    </a>
                    <div class="search" id="searchinput1">
                        <input type="search">
                        <i class="bi bi-search srchicon"></i>
                    </div>
                    <a href="">
                        <i class="bi bi-person-fill" id="user-mb"></i>
                    </a>
                </ul>
            </div>
            <div class="nav">
                <ul>
                    <a href="">
                        <li>Home</li>
                    </a>

                    <a href="">
                        <li>Recipe</li>
                    </a>

                    <a href="">
                        <li>Search</li>
                    </a>

                    <a href="">
                        <li>Login/sign-Up</li>
                    </a>

                    <a href="">
                        <li>About</li>
                    </a>

                    <a href="">
                        <li>Review Feedback</li>
                    </a>
                     
                    <a href="">
                        <li>Home</li>
                    </a>
                </ul>
            </div>
           </div>
    <!-- Navbar Activation Toogle End Here-->

    <!--Navbar Content Session Begins Here-->
            <div class="logo">
                <img src="./icons/e-logo.png" alt="" style="width: 40px; height: 40px;">
                <div>
                    <h4>CULINARY COVE</h4>
                </div>               
            </div>

            <div class="bar">
                <i class="bi bi-list"></i>
                <i class="bi bi-x-lg" id="hdcross"></i>
            </div>

            <!--Menu List Begins-->
            <div class="nav">
                <ul>
                    <a href="#">
                        <li>Home</li>
                    </a>
                    <a href="recipes.php">
                        <li>Recipe</li>
                    </a>
                    <!-- <a href="search.html">
                        <li>Search</li>
                    </a> -->
                    <a href="login-signup.php">
                        <li>Login/Sign-Up</li>
                    </a>
                    <a href="about.html">
                        <li>About</li>
                    </a>
                    <a href="review-feedback.html">
                        <li>Review Feedback</li>
                    </a>
                </ul>                
            </div>
            <!--Menu List End Here-->
            
            <!--Account Section Begins Here-->
            <div class="account">
                <ul>
                    <a href="#">
                        <li>
                            <i class="bi bi-house-fill"></i>
                        </li>
                    </a>
                    <a href="#">
                        <li>
                            <i class="bi bi-search" id="searchicon2 "></i>
                        </li>
                    </a>

                    <div class="search" id="searchinput2">
                        <input type="search">
                        <i class="bi bi-search srchicon"></i>
                    </div>

                    <a href="#">
                        <li>
                            <i class="bi bi-person-fill" id="user-lap"></i>
                        </li>
                    </a>
                </ul>
            </div>
        </div>      
    </header>
    <!--Header (Navbar) End Here-->
   
    <div class="r-container">
    <h2>Recipes</h2>
    <p><a href="logout.php">Back</a></p>
    <a href="recipes.php">All Recipes</a>
    
    <!-- Search form -->
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search recipes by keyword">
        <select name="category">
            <option value="">Select category</option>
            <option value="Breakfast">Breakfast Meal</option>
            <option value="Lunch">Lunch Meal</option>
            <option value="Supper">Supper Meal</option>
        </select>
        <select name="location">
            <option value="">Select location</option>
            
            <option value="Nigeria">Nigeria</option>
            <option value="United Kingdom">United Kingdom</option>
        
        </select>
        <button type="submit">Search</button>
    </form>
    
   
    <!-- Display recipes -->
    <div class="recipe-container">
    
        <?php foreach ($allRecipes as $recipe): ?>
            <div class="recipe-card">
                <img src="<?= $recipe['image_url'] ?>" alt="<?= $recipe['title'] ?>">
                <h3><?= $recipe['title'] ?></h3>
                <p>By: <?= $recipe['username'] ?></p>
                <a href="view_recipe.php?recipe_id=<?= $recipe['recipe_id'] ?>">View</a>
                
            </div>
        <?php endforeach; ?>
    </div>

    </div>
    
     <!--Footer Begins Here-->
     <div class="footer">
            <div class="footer-1">
                <div class="logo">
                    <img src="./icons/e-logo.png" alt="" style="width: 40px; height: 40px;">
                    <div>
                        <h4>CULINARY COVE</h4>
                    </div>
                </div>
                <div>
                    <address>
                        <p>Email: a.adedokun2@rgu.ac.uk</p>
                        <p>YouTube: ENGINEER </p>
                        <p>Garthdee House, Garthdee Rd, Garthdee, <br>city, Aberdeen <br>Scotland</p>
                    </address>
                </div>
            </div>

            <div class="footer-2">
                <img src="./icons//e-logo.png" alt="">
                <h2>Powered by <em>ENGINEER WHALE</em></h2>
            </div>
        </div>
        <!--Footer End Here-->
    
</body>
</html>
