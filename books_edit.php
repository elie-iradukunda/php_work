<?php
require 'auth.php';
require 'db.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT id, title, author, isbn, published_year FROM books WHERE id = ?');
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    die('Book not found');
}

$title  = $book['title'];
$author = $book['author'];
$isbn   = $book['isbn'];
$year   = $book['published_year'];
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $isbn   = trim($_POST['isbn'] ?? '');
    $year   = trim($_POST['published_year'] ?? '');

    if ($title === '' || $author === '') {
        $error = 'Please provide a title and an author.';
    } else {
        $stmt = $pdo->prepare('UPDATE books SET title = ?, author = ?, isbn = ?, published_year = ? WHERE id = ?');
        $stmt->execute([$title, $author, $isbn ?: null, $year ?: null, $id]);
        header('Location: books_list.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <h1>Edit Book</h1>
        <p>Update book information and keep your catalogue accurate.</p>
    </aside>

    <main class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Edit book</div>
                <div class="card-subtitle">ID #<?php echo $id; ?></div>
            </div>
            <a href="books_list.php" class="btn btn-secondary btn-sm">Back to list</a>
        </div>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="books_edit.php?id=<?php echo $id; ?>">
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
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </main>
</div>
</body>
</html>
