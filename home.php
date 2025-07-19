<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - LogBook</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 2rem;
        }
        .header {
            background-color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .welcome-message {
            color: #333;
            font-size: 1.2rem;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="welcome-message">
            Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    
    <div class="content">
        <h1>LogBook Dashboard</h1>
        <p>You have successfully logged in to your LogBook account.</p>
        <p>This is your home page where you can manage your logbook entries.</p>
    </div>
</body>
</html>