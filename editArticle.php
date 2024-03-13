<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'task_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmt = $pdo->query("SELECT * FROM News");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $stmtAuthors = $pdo->query("SELECT * FROM Author");
    $authors = $stmtAuthors->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $articleId = $_POST['article_id'];
        $title = $_POST['title'];
        $text = $_POST['text'];
        $authorId = $_POST['author'];


        $stmtUpdateArticle = $pdo->prepare("UPDATE News SET title = :title, text = :text WHERE id = :article_id");
        $stmtUpdateArticle->bindParam(':title', $title);
        $stmtUpdateArticle->bindParam(':text', $text);
        $stmtUpdateArticle->bindParam(':article_id', $articleId);
        $stmtUpdateArticle->execute();


        $stmtUpdateArticleAuthor = $pdo->prepare("UPDATE ArticleAuthor SET author_id = :author_id WHERE article_id = :article_id");
        $stmtUpdateArticleAuthor->bindParam(':author_id', $authorId);
        $stmtUpdateArticleAuthor->bindParam(':article_id', $articleId);
        $stmtUpdateArticleAuthor->execute();

        echo "Article updated successfully.";
        header("Location: main.php");
    } else {
        echo "Error: Invalid request method.";
    }
} catch (PDOException $e) {
    echo "Error updating article: " . $e->getMessage();
}
?>
