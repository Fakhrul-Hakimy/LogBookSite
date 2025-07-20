<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Initialize variables
$logTitle = $_POST['logTitle'] ?? '';
$logDate = $_POST['logDate'] ?? '';
$timeIn = $_POST['Timein'] ?? '';
$timeOut = $_POST['Timeout'] ?? '';
$logDescription = $_POST['logDescription'] ?? '';
$images = $_FILES['picture'] ?? null;
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($logTitle) || empty($logDate) || empty($timeIn) || empty($timeOut) || empty($logDescription) || !$images) {
        $error_message = "All fields are required.";
    } else {
        try {
            // Insert log into `logs` table
            $stmt = $conn->prepare("INSERT INTO logs (title, date, time_in, time_out, description) VALUES (:title, :date, :time_in, :time_out, :description)");
            $stmt->bindParam(':title', $logTitle);
            $stmt->bindParam(':date', $logDate);
            $stmt->bindParam(':time_in', $timeIn);
            $stmt->bindParam(':time_out', $timeOut);
            $stmt->bindParam(':description', $logDescription);
            $stmt->execute();
            $logId = $conn->lastInsertId();

            $success_message = "Log entry created successfully.";

            // Upload multiple images
            $targetDir = "uploads/";
            for ($i = 0; $i < count($images['name']); $i++) {
                if ($images['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $images['tmp_name'][$i];
                    $fileName = basename($images['name'][$i]);
                    $targetFile = $targetDir . uniqid() . '_' . $fileName;
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                    $check = getimagesize($tmpName);
                    if ($check !== false) {
                        if (move_uploaded_file($tmpName, $targetFile)) {
                            $imageStmt = $conn->prepare("INSERT INTO log_images (log_id, image_path) VALUES (:log_id, :image_path)");
                            $imageStmt->bindParam(':log_id', $logId);
                            $imageStmt->bindParam(':image_path', $targetFile);
                            $imageStmt->execute();
                        } else {
                            $error_message .= " Failed to upload: " . $fileName;
                        }
                    } else {
                        $error_message .= " Not an image: " . $fileName;
                    }
                }
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
    <title>Log Processing Result - LogBook</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .message-container {
            background-color: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            text-align: center;
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
        
        .success-message {
            background-color: #f0fff4;
            color: #2d7d32;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #48bb78;
            font-weight: 500;
            font-size: 1.1rem;
        }
        
        .error-message {
            background-color: #fee;
            color: #c53030;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #e53e3e;
            font-weight: 500;
            font-size: 1.1rem;
        }
        
        .back-btn {
            display: inline-block;
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .back-btn.secondary {
            background: #6c757d;
        }
        
        .back-btn.secondary:hover {
            background: #5a6268;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
        }
        
        h2 {
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }
        
        .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .success-icon {
            color: #48bb78;
        }
        
        .error-icon {
            color: #e53e3e;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <?php if (!empty($success_message)): ?>
            <div class="icon success-icon">✓</div>
            <h2>Success!</h2>
            <div class="success-message">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
            <a href="home.php" class="back-btn">Back to Home</a>
            <a href="home.php#add-log" class="back-btn secondary">Add Another Log</a>
        <?php elseif (!empty($error_message)): ?>
            <div class="icon error-icon">✗</div>
            <h2>Error Occurred</h2>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
            <a href="javascript:history.back()" class="back-btn">Go Back</a>
            <a href="home.php" class="back-btn secondary">Back to Home</a>
        <?php else: ?>
            <div class="icon">ℹ</div>
            <h2>No Action Taken</h2>
            <p>No form data was submitted.</p>
            <a href="home.php" class="back-btn">Back to Home</a>
        <?php endif; ?>
    </div>
</body>
</html>