<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'task_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->prepare("
            SELECT author_id, COUNT(*) AS article_count 
            FROM ArticleAuthor 
            JOIN News ON ArticleAuthor.article_id = News.id 
            WHERE creation_date >= DATE_SUB(NOW(), INTERVAL 1 WEEK) 
            GROUP BY author_id 
            ORDER BY article_count DESC 
            LIMIT 3
        ");
        $stmt->execute();
        $topAuthors = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $authorId = $row['author_id'];
            $authorStmt = $pdo->prepare("SELECT * FROM Author WHERE id = :id");
            $authorStmt->execute(['id' => $authorId]);
            $author = $authorStmt->fetch(PDO::FETCH_ASSOC);
            if ($author) {
                $topAuthors[] = [
                    'author' => $author['name'],
                    'article_count' => $row['article_count']
                ];
            }
        }
        echo json_encode($topAuthors);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

?>
