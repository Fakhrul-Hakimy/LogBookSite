<?php
session_start();

include 'db.php';

$EMAIL = $_POST['email'] ?? '';
$PASSWORD = $_POST['password'] ?? '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($EMAIL) && !empty($PASSWORD)) {
    try {
        // Prepare SQL statement to find user by email
        $stmt = $conn->prepare("SELECT id, email, password FROM account WHERE email = :email");
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
                // Redirect back to index.php with error and email
                header("Location: index.php?error=" . urlencode("Invalid email or password.") . "&email=" . urlencode($EMAIL));
                exit();
            }
        } else {
            // Redirect back to index.php with error and email
            header("Location: index.php?error=" . urlencode("Invalid email or password.") . "&email=" . urlencode($EMAIL));
            exit();
        }
    } catch(PDOException $e) {
        // Redirect back to index.php with database error and email
        header("Location: index.php?error=" . urlencode("Database error occurred. Please try again.") . "&email=" . urlencode($EMAIL));
        exit();
    }
}
?>