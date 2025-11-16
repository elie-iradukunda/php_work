<?php
require 'auth.php';
require 'db.php';

$stmt = $pdo->query('SELECT id, title, author, isbn, published_year FROM books ORDER BY id DESC');
$books = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <h1>Library Dashboard</h1>
        <p>Manage the collection of books. Access is restricted to logged in users.</p>
        <ul>
            <li>• Secure session-based access</li>
            <li>• Full CRUD for books</li>
        </ul>
    </aside>

    <main class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Books</div>
                <div class="card-subtitle">Logged in as <?php echo htmlspecialchars($currentUser['username']); ?></div>
            </div>
            <a href="logout.php" class="btn btn-secondary btn-sm">Logout</a>
        </div>

        <div class="toolbar">
            <span class="muted">Total books: <?php echo count($books); ?></span>
            <a href="books_create.php" class="btn btn-primary btn-sm">Add Book</a>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo (int)$book['id']; ?></td>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                    <td><?php echo htmlspecialchars($book['published_year']); ?></td>
                    <td class="actions">
                        <a href="books_edit.php?id=<?php echo (int)$book['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                        <a href="books_delete.php?id=<?php echo (int)$book['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this book?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</div>
</body>
</html>
