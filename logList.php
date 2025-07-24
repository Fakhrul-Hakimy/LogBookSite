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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log List - LogBook</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
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

        .header {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            margin-bottom: 2rem;
            text-align: center;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 1rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: #764ba2;
        }

        .back-link::before {
            content: "← ";
        }

        h1 {
            color: #333;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        .log-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 2rem;
        }

        .log-card {
            background-color: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            border: 1px solid #e1e5e9;
        }

        .log-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        .log-header {
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .log-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .log-date {
            color: #667eea;
            font-weight: 500;
            font-size: 1rem;
        }

        .log-details {
            margin-bottom: 1.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: #555;
            font-size: 0.9rem;
        }

        .detail-value {
            color: #333;
            font-size: 0.9rem;
        }

        .time-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .log-description {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            color: #555;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .images-section {
            margin-top: 1.5rem;
        }

        .images-header {
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.75rem;
        }

        .image-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
            cursor: pointer;
        }

        .image-item:hover {
            transform: scale(1.05);
        }

        .image-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }

        .no-images {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .no-logs {
            background-color: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .no-logs-icon {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        .no-logs-text {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .add-log-btn {
            display: inline-block;
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .add-log-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* Modal for image viewing */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            max-height: 80%;
            object-fit: contain;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
        }

        .close:hover {
            color: #bbb;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .log-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .log-card {
                padding: 1.5rem;
            }

            h1 {
                font-size: 2rem;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }

            .image-gallery {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }

            .image-item img {
                height: 100px;
            }
        }

        .stats-bar {
            background-color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 600;
            color: #667eea;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="home.php" class="back-link">Back to Home</a>
            <h1>Log Entries</h1>
            <p class="subtitle">View and manage your activity logs</p>
        </div>

        <?php if (count($logs) > 0): ?>
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($logs); ?></div>
                    <div class="stat-label">Total Logs</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo date('M Y'); ?></div>
                    <div class="stat-label">Current Period</div>
                </div>
                <div class="stat-item">
                    <a href="addLog.php" class="add-log-btn">+ Add New Log</a>
                </div>
            </div>

            <div class="log-grid">
            <div class="log-grid">
                <?php foreach ($logs as $log): ?>
                    <div class="log-card">
                        <div class="log-header">
                            <div class="log-title"><?php echo htmlspecialchars($log['title']); ?></div>
                            <div class="log-date"><?php echo date('F j, Y', strtotime($log['date'])); ?></div>
                        </div>

                        <div class="log-details">
                            <div class="detail-row">
                                <span class="detail-label">Time In:</span>
                                <span class="detail-value time-badge"><?php echo date('g:i A', strtotime($log['time_in'])); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Time Out:</span>
                                <span class="detail-value time-badge"><?php echo date('g:i A', strtotime($log['time_out'])); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Duration:</span>
                                <span class="detail-value">
                                    <?php 
                                    $start = new DateTime($log['time_in']);
                                    $end = new DateTime($log['time_out']);
                                    $duration = $start->diff($end);
                                    echo $duration->format('%h hours %i minutes');
                                    ?>
                                </span>
                            </div>
                        </div>

                        <div class="log-description">
                            <?php echo nl2br(htmlspecialchars($log['description'])); ?>
                        </div>

                        <div class="images-section">
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM log_images WHERE log_id = :log_id");
                            $stmt->bindParam(':log_id', $log['id']);
                            $stmt->execute();
                            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (!empty($images)): ?>
                                <div class="images-header">📸 Images (<?php echo count($images); ?>)</div>
                                <div class="image-gallery">
                                    <?php foreach ($images as $image): ?>
                                        <div class="image-item" onclick="openModal('<?php echo htmlspecialchars($image['image_path']); ?>')">
                                            <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Log Image">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="no-images">No images attached to this log</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-logs">
                <div class="no-logs-icon">📝</div>
                <div class="no-logs-text">No log entries found</div>
                <p style="color: #999; margin-bottom: 2rem;">Start by creating your first log entry</p>
                <a href="addLog.php" class="add-log-btn">+ Create Your First Log</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script>
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImg.src = imageSrc;
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>
