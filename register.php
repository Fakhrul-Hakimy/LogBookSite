<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

include 'db.php';

$EMAIL = $_POST['email'] ?? '';
$PASSWORD = $_POST['password'] ?? '';
$CONFIRM_PASSWORD = $_POST['confirm_password'] ?? '';
$ROLE = $_POST['role'] ?? '';
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($EMAIL) || empty($PASSWORD) || empty($CONFIRM_PASSWORD) || empty($ROLE)) {
        $error_message = "All fields are required.";
    } elseif ($PASSWORD !== $CONFIRM_PASSWORD) {
        $error_message = "Passwords do not match.";
    } elseif (!in_array($ROLE, ['editor', 'reviewer'])) {
        $error_message = "Please select a valid role.";
    } else {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM account WHERE email = :email");
            $stmt->bindParam(':email', $EMAIL);
            $stmt->execute();
            if ($stmt->fetch()) {
                $error_message = "Email already registered.";
            } else {
                // Hash the password
                $hashed_password = password_hash($PASSWORD, PASSWORD_DEFAULT);

                // Insert new user
                $insert = $conn->prepare("INSERT INTO account (email, password, role) VALUES (:email, :password, :role)");
                $insert->bindParam(':email', $EMAIL);
                $insert->bindParam(':password', $hashed_password);
                $insert->bindParam(':role', $ROLE);
                $insert->execute();

                $success_message = "Registration successful! You can now <a href='login.php'>log in</a>.";
            }
        } catch (PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LogBook</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .register-container {
            background-color: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .register-btn {
            width: 100%;
            padding: 0.875rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .register-btn:active {
            transform: translateY(0);
        }
        
        .error-message {
            background-color: #fee;
            color: #c53030;
            padding: 0.875rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #e53e3e;
            font-weight: 500;
        }
        
        .success-message {
            background-color: #f0fff4;
            color: #2d7d32;
            padding: 0.875rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #48bb78;
            font-weight: 500;
        }
        
        .success-message a {
            color: #2d7d32;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 1px solid #2d7d32;
        }
        
        .success-message a:hover {
            color: #1a5a1a;
            border-bottom-color: #1a5a1a;
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .register-container {
                padding: 2rem;
                margin: 10px;
            }
            
            h2 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Create Account</h2>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($EMAIL); ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="">Select a role</option>
                    <option value="editor" <?php echo ($ROLE === 'editor') ? 'selected' : ''; ?>>Editor</option>
                    <option value="reviewer" <?php echo ($ROLE === 'reviewer') ? 'selected' : ''; ?>>Reviewer</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="register-btn">Create Account</button>
        </form>
        
        <div class="login-link">
            Back to settings <a href="settings.php">settings</a>
        </div>
    </div>
</body>
</html>
