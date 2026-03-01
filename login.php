<?php
session_start();
require __DIR__ . '/src/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // NOTE: For a real project, store hashed passwords in the database.
    // This is a simple example for demonstration.
    if ($username === 'admin' && $password === 'password123') {
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | UDD Feedback</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .login-container { max-width: 400px; margin: 100px auto; }
        .alert { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 style="text-align:center; color: #3498db;">UDD System Login</h2>
        <?php if ($error): ?>
            <div class="alert"><?= h($error) ?></div>
        <?php endif; ?>
        <form method="POST" class="card">
            <label>Username</label>
            <input type="text" name="username" required>
            
            <label>Password</label>
            <input type="password" name="password" required>
            
            <button type="submit" class="btn">Sign In</button>
        </form>
    </div>
</body>
</html>