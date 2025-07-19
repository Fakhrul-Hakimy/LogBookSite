<?php
session_start();

include 'db.php';

$EMAIL = $_POST['email'] ?? '';
$PASSWORD = $_POST['password'] ?? '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($EMAIL) && !empty($PASSWORD)) {
    try {
        // Prepare SQL statement to find user by email
        $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = :email");
        $stmt->bindParam(':email', $EMAIL);
        $stmt->execute();
        
        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Verify password (assuming passwords are hashed)
            if (password_verify($PASSWORD, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;
                
                // Redirect to home page
                header("Location: home.php");
                exit();
            } else {
                $error_message = "Invalid email or password.";
            }
        } else {
            $error_message = "Invalid email or password.";
        }
    } catch(PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LogBook</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        .login-btn:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: #dc3545;
            margin-top: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login to LogBook</h2>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($EMAIL); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>
