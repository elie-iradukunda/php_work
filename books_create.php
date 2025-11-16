<?php
require 'auth.php';
require 'db.php';

$title  = '';
$author = '';
$isbn   = '';
$year   = '';
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $isbn   = trim($_POST['isbn'] ?? '');
    $year   = trim($_POST['published_year'] ?? '');

    if ($title === '' || $author === '') {
        $error = 'Please provide a title and an author.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO books (title, author, isbn, published_year) VALUES (?, ?, ?, ?)');
        $stmt->execute([$title, $author, $isbn ?: null, $year ?: null]);
        header('Location: books_list.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <h1>Add Book</h1>
        <p>Capture key information about a book in the library catalogue.</p>
    </aside>

    <main class="card">
        <div class="card-header">
            <div>
                <div class="card-title">New book</div>
                <div class="card-subtitle">Fill in the details below.</div>
            </div>
            <a href="books_list.php" class="btn btn-secondary btn-sm">Back to list</a>
        </div>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="books_create.php">
            <div class="form-row">
                <label>Title</label>
                <input type="text" name="title" required value="<?php echo htmlspecialchars($title); ?>">
            </div>
            <div class="form-row">
                <label>Author</label>
                <input type="text" name="author" required value="<?php echo htmlspecialchars($author); ?>">
            </div>
            <div class="form-row">
                <label>ISBN</label>
                <input type="text" name="isbn" value="<?php echo htmlspecialchars($isbn); ?>">
            </div>
            <div class="form-row">
                <label>Year</label>
                <input type="text" name="published_year" value="<?php echo htmlspecialchars($year); ?>">
            </div>
            <div class="form-row" style="margin-top:8px;">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </main>
</div>
</body>
</html>
