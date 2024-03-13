<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'task_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['author_id'])) {
        $authorId = $_GET['author_id'];
        $stmt = $pdo->prepare("SELECT * FROM ArticleAuthor WHERE author_id = :author_id");
        $stmt->execute(['author_id' => $authorId]);
        $articles = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $articleStmt = $pdo->prepare("SELECT * FROM News WHERE id = :id");
            $articleStmt->execute(['id' => $row['article_id']]);
            $article = $articleStmt->fetch(PDO::FETCH_ASSOC);
            if ($article) {
                $articles[] = $article;
            }
        }
        echo json_encode($articles);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

?>
