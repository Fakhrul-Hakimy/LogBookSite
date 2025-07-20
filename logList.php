<?php

session_start();
include 'db.php';
// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
// Fetch logs from the database
try {
    $stmt = $conn->prepare("SELECT * FROM logs ORDER BY date DESC");
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}
if (isset($error_message)) {
    echo "<p>Error: $error_message</p>";
} else {
    if (count($logs) > 0) {
        echo "<ul>";
        foreach ($logs as $log) {
            echo "<li>" . htmlspecialchars($log['title']) . " - " . htmlspecialchars($log['date']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No logs found.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log List</title>
</head>
<body>

    <h1>Log List</h1>
    <p>Here you can view the logs.</p>


    
</body>
</html>