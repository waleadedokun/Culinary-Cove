

<?php
session_start(); 

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $mysqli = require __DIR__ . "/connect_db.php";

    $email = $_POST["email"];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $sql = sprintf("SELECT * FROM users
                    WHERE email = '%s'",
                   $mysqli->real_escape_string($email));
    
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password']) && $user['role'] === $role) {
        // Authentication successful
        $_SESSION['user_id'] = $user['user_id']; 
        $_SESSION['role'] = $role; 

        if ($role === 'recipe_seeker') {
            header("Location: recipes.php"); // Redirect to browse recipe page
        } elseif ($role === 'cook') {
            header("Location: cook_home.php"); 
        } 
        exit();
    } else {
        // Authentication failed
        $is_invalid = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" 
    integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
      integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <link rel="shortcut icon" href="./icons/cutleries.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CULINARY-COVE</title>
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
    
    <h1>Login</h1>
    
    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>
    
    <form method="post">
        <div>
        <label for="email">email</label>
        <input type="email" name="email" id="email"
               value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        </div>
        
        <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        </div>

        <div>
        <label for="role">Role:</label>
        <select name="role" id="role" required>
          <option value="recipe_seeker">Recipe Seeker</option>
          <option value="cook">Cook</option>
          
        </select>
      </div>
        
        <input type="submit" value="Login">
    </form>
    <div class="">
      <p class="">New User?</p>
      
    </div>


    <h2>Register</h2>
    <form action="signup.php" method="post" id="signup" novalidate>
    <div>
      <label for="name">Username:</label>
      <input
        type="text"
        id="username"
        name="username"
        placeholder="Full Name"
      />
    </div>

    <div>
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" />
    </div>

    <div>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" />
    </div>

    <div>
      <label for="password_confirmation">Repeat password</label>
      <input
        type="password"
        id="password_confirmation"
        name="password_confirmation"
      />
    </div>

    <div>
      <label for="role">Role:</label>
      <select name="role" id="role" required>
        <option value="recipe_seeker">Recipe Seeker</option>
        <option value="cook">Cook</option>
      </select>
    </div>

    <input type="submit" value="Sign Up" />
  </form>

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

