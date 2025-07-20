<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Fetch all logs
try {
    $stmt = $conn->prepare("SELECT * FROM logs ORDER BY date DESC");
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log List</title>
</head>
<body>
    <h1>Log List</h1>
    <p>Here you can view the logs.</p>

    <?php 
    if (count($logs) > 0) {
        echo "<div class='log-list'>";
        foreach ($logs as $log) {
            echo "<hr>";
            echo "<p>Title: " . htmlspecialchars($log['title']) . "</p>";
            echo "<p>Date: " . htmlspecialchars($log['date']) . "</p>";
            echo "<p>Time in: " . htmlspecialchars($log['time_in']) . "</p>";
            echo "<p>Time out: " . htmlspecialchars($log['time_out']) . "</p>";
            echo "<p>Description: " . htmlspecialchars($log['description']) . "</p>";

            // Fetch images for this log
            $stmt = $conn->prepare("SELECT * FROM log_images WHERE log_id = :log_id");
            $stmt->bindParam(':log_id', $log['id']);
            $stmt->execute();
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($images)) {
                echo "<p>Images:</p>";
                foreach ($images as $image) {
                    echo "<img src='" . htmlspecialchars($image['image_path']) . "' alt='Log Image' style='max-width: 200px; max-height: 200px; margin: 5px;'>";
                }
            } else {
                echo "<p>No images available for this log.</p>";
            }

            echo "<hr>";
        }
        echo "</div>";
    } else {
        echo "<p>No logs found.</p>";
    }
    ?>
</body>
</html>
