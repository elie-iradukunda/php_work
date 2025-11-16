<?php
// login.php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true); // prevent session fixation
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // simple remember-username cookie for convenience (not auto-login)
        if ($remember) {
            setcookie('remember_username', $user['username'], time() + 60 * 60 * 24 * 30, '/');
        } else {
            setcookie('remember_username', '', time() - 3600, '/');
        }

        header('Location: books_list.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <h1>Library System</h1>
        <p>Secure login protects access to all book management features.</p>
        <ul>
            <li>• Session-based authentication</li>
            <li>• Remember username (cookie)</li>
            <li>• Protected CRUD operations</li>
        </ul>
    </aside>

    <main class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Sign in</div>
                <div class="card-subtitle">Use your account to access the library dashboard.</div>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div class="form-row">
                <label>Username</label>
                <input type="text" name="username" required
                       value="<?php echo htmlspecialchars($_COOKIE['remember_username'] ?? ''); ?>">
            </div>
            <div class="form-row">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-row" style="display:flex;align-items:center;justify-content:space-between;">
                <label style="display:flex;align-items:center;gap:6px;margin:0;">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <span class="muted">No account? <a href="register.php">Register</a></span>
            </div>

            <div class="form-row" style="margin-top:8px;">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </main>
</div>
</body>
</html>
