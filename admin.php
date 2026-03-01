<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/src/db.php';

$db = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $admin_note = trim($_POST['admin_note']);

    $stmt = $db->prepare("UPDATE complaints SET status = ?, admin_note = ? WHERE id = ?");
    $stmt->execute([$status, $admin_note, $id]);
    header('Location: admin.php');
    exit;
}

$complaints = $db->query("SELECT * FROM complaints ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | UDD</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .udd-title {
            color: #3498db; /* Light Blue Color */
        }
        .student-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="udd-title">Universidad De Dagupan - Admin Dashboard</h2>
        <?php foreach ($complaints as $c): ?>
            <form method="POST" class="card">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <div class="row">
                    <strong>ID: <?= h($c['tracking_id']) ?></strong>
                    <span class="tag"><?= h($c['category']) ?></span>
                </div>
                <h3><?= h($c['subject']) ?></h3>
                <p><?= nl2br(h($c['description'])) ?></p>
                
                <div class="student-info">
                    <p><strong>Name:</strong> <?= h($c['student_name']) ?></p>
                    <p><strong>Student ID:</strong> <?= h($c['student_id']) ?></p>
                </div>
                
                <label>Status</label>
                <select name="status">
                    <?php foreach (['Pending', 'Under Review', 'Resolved', 'Dismissed'] as $s): ?>
                        <option value="<?= $s ?>" <?= $c['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
                
                <label>Admin Note</label>
                <textarea name="admin_note"><?= h($c['admin_note']) ?></textarea>
                
                <button type="submit" class="btn">Update</button>
            </form>
        <?php endforeach; ?>
    </div>
</body>
</html>