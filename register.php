<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $error = 'Username, email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // check duplicate username OR email
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $error = 'That username or email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $insert->execute([$username, $email, $hash]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <h1>Create Account</h1>
        <p>Register a new user to access the secure library dashboard.</p>
        <ul>
            <li>• Passwords are hashed with <code>password_hash</code></li>
            <li>• Sessions protect all CRUD pages</li>
        </ul>
    </aside>

    <main class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Sign up</div>
                <div class="card-subtitle">Fill in your details to create an account.</div>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="register.php">
            <div class="form-row">
                <label>Username</label>
                <input type="text" name="username" required
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="form-row">
                <label>Email</label>
                <input type="email" name="email" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-row">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-row">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <div class="form-row" style="margin-top:8px;">
                <button type="submit" class="btn btn-primary">Register</button>
                <a href="login.php" class="btn btn-secondary" style="margin-left:8px;">Back to login</a>
            </div>
        </form>
    </main>
</div>
</body>
</html>
