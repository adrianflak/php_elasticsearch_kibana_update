<?php
ini_set("log_errors", 1);
ini_set("error_log", "/var/www/php_elasticsearch_kibana/logs/app.log");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php'; // <-- plik z połączeniem PDO

$query = $_GET['q'] ?? '';

if ($query) {
    echo "<p><strong>Wyniki dla: " . htmlspecialchars($query) . "</strong></p>";

    // Szukanie w MySQL
    $stmt = $pdo->prepare("SELECT title FROM articles WHERE title LIKE :query");
    $stmt->execute(['query' => '%' . $query . '%']);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rows) {
        echo "<p><em>Źródło: MySQL</em></p>";
        foreach ($rows as $row) {
            echo "<p>" . htmlspecialchars($row['title']) . "</p>";
        }
    } else {
        echo "<p>Brak wyników.</p>";
    }
} else {
?>
<form>
    <input name="q" placeholder="Szukaj tytułu..." />
    <button type="submit">Szukaj</button>
</form>
<?php } ?>
