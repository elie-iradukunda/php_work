<?php
require 'auth.php';
require 'db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
    $stmt->execute([$id]);
}

header('Location: books_list.php');
exit;

