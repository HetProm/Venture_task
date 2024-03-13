<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'task_db';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS News (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            text TEXT NOT NULL,
            creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS Author (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS ArticleAuthor (
            article_id INT,
            author_id INT,
            FOREIGN KEY (article_id) REFERENCES News(id) ON DELETE CASCADE,
            FOREIGN KEY (author_id) REFERENCES Author(id) ON DELETE CASCADE,
            PRIMARY KEY (article_id, author_id)
        )
    ");

    $stmt = $pdo->query("SELECT COUNT(*) FROM Author");
    $rowCount = $stmt->fetchColumn();
    if ($rowCount == 0) {
        $pdo->exec("
            INSERT INTO Author (name) VALUES ('John Doe');
            INSERT INTO Author (name) VALUES ('Alice Smith');
            INSERT INTO Author (name) VALUES ('Bob Johnson');
        ");

        $pdo->exec("
            INSERT INTO News (title, text) VALUES ('Title 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
            INSERT INTO News (title, text) VALUES ('Title 2', 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
            INSERT INTO News (title, text) VALUES ('Title 3', 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.');
        ");

        $pdo->exec("
            INSERT INTO ArticleAuthor (article_id, author_id) VALUES (1, 1);
            INSERT INTO ArticleAuthor (article_id, author_id) VALUES (1, 2);
            INSERT INTO ArticleAuthor (article_id, author_id) VALUES (2, 2);
            INSERT INTO ArticleAuthor (article_id, author_id) VALUES (3, 3);
            INSERT INTO ArticleAuthor (article_id, author_id) VALUES (3, 1);
        ");
        
        error_log("Test data has been added.");
    } else {
        error_log("Test data already exists.");
    }
} catch(PDOException $e) {
    echo "Error creating database and tables: " . $e->getMessage();
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing Endpoints</title>
</head>
<body>
    <button onclick="openEndpoint('http://localhost/Venture_task/getArticleById.php?id=1')">Get article by id 1</button>
    <button onclick="openEndpoint('http://localhost/Venture_task/getArticlesByAuthor.php?author_id=1')">Get articles by author id 1</button>
    <button onclick="openEndpoint('http://localhost/Venture_task/getTopAuthorsLastWeek.php')">Get top authors of last week</button>

    <script>
        function openEndpoint(url) {
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
