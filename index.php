<?php


ini_set("log_errors", 1);
ini_set("error_log", "/var/www/php_elasticsearch_kibana/logs/app.log");

trigger_error("Testowy błąd do logów", E_USER_WARNING);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';
require 'elastic.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];

    // Dodaj do MySQL
    $stmt = $pdo->prepare("INSERT INTO articles (title) VALUES (:title)");
    $stmt->execute(['title' => $title]);
    $id = $pdo->lastInsertId();

    // Elasticsearch (v8.17 style)
    $response = $client->index([
        'index' => 'articles',
        'id'    => $id,
        'body'  => ['title' => $title]
    ]);

    echo "Dodano artykuł!";
} else {
?>
<form method="POST">
    <input name="title" placeholder="Tytuł" />
    <button type="submit">Zapisz</button>
</form>
<?php } ?>
