<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'task_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmtAuthors = $pdo->query("SELECT * FROM Author");
    $authors = $stmtAuthors->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['title'];
        $text = $_POST['text'];
        $authorId = $_POST['author'];

        $stmt = $pdo->prepare("INSERT INTO News (title, text) VALUES (:title, :text)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':text', $text);
        $stmt->execute();

        $articleId = $pdo->lastInsertId(); 

        $stmtArticleAuthor = $pdo->prepare("INSERT INTO ArticleAuthor (article_id, author_id) VALUES (:article_id, :author_id)");
        $stmtArticleAuthor->bindParam(':article_id', $articleId);
        $stmtArticleAuthor->bindParam(':author_id', $authorId);
        $stmtArticleAuthor->execute();

        header("Location: main.php");
        exit();
    } else {
        echo "Error: Invalid request method.";
    }
} catch (PDOException $e) {
    echo "Error adding article: " . $e->getMessage();
}
?>
