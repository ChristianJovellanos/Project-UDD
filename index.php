<?php
session_start();
// Redirect to login if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/src/db.php';

$message = "";
$tracking_id = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate a unique tracking ID
    $tracking_id = strtoupper(bin2hex(random_bytes(4)));
    $category = $_POST['category'];
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $name = trim($_POST['name'] ?: 'Anonymous');
    $student_id = trim($_POST['student_id'] ?: null);

    if (!empty($subject) && !empty($description)) {
        $stmt = db()->prepare("INSERT INTO complaints (tracking_id, category, subject, description, student_name, student_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$tracking_id, $category, $subject, $description, $name, $student_id]);
        $message = "Feedback submitted successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UDD Feedback & Complaint System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .udd-title {
            color: #3498db; /* Light Blue Color */
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1 class="udd-title">Universidad De Dagupan</h1>
            <h3>Feedback and Complaint System</h3>
        </header>

        <?php if ($message): ?>
            <div class="alert success">
                <strong><?= h($message) ?></strong><br>
                Your Tracking ID: <code><?= h($tracking_id) ?></code>
            </div>
        <?php endif; ?>

        <form method="POST" class="card">
            <label>Category</label>
            <select name="category" required>
                <option value="Facility">Facility/Maintenance</option>
                <option value="Bullying">Bullying/Harassment</option>
                <option value="Academic">Academic Issue</option>
                <option value="Other">Other</option>
            </select>

            <label>Subject</label>
            <input type="text" name="subject" required>

            <label>Description</label>
            <textarea name="description" rows="5" required></textarea>

            <label>Full Name</label>
            <input type="text" name="name" placeholder="Juan Dela Cruz" required>

            <label>Student ID Number</label>
            <input type="text" name="student_id" placeholder="2023-XXXXX" required>

            <button type="submit" class="btn">Submit Feedback</button>
        </form>
        
        <div style="text-align:center; margin-top:20px;">
            <a href="check.php">Already submitted? Check your status here.</a>
        </div>
    </div>
</body>
</html>