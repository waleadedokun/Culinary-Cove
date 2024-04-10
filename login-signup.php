

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

</head>
<body>
    
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
    
</body>
</html>

