<?php
ini_set("log_errors", 1);
ini_set("error_log", "/var/www/php_elasticsearch_kibana/logs/app.log");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';      // <-- dodaj połączenie z MySQL
require 'elastic.php'; // <-- połączenie z Elasticsearch

$query = $_GET['q'] ?? '';

if ($query) {
    echo "<p><strong>Wyniki dla: " . htmlspecialchars($query) . "</strong></p>";

    // Szukanie w Elasticsearch
    $results = $client->search([
        'index' => 'articles',
        'body'  => [
            'query' => [
                'match' => ['title' => $query]
            ]
        ]
    ]);

    $hits = $results['hits']['hits'];

    if (count($hits) > 0) {
        echo "<p><em>Źródło: Elasticsearch</em></p>";
        foreach ($hits as $hit) {
            echo "<p>" . htmlspecialchars($hit['_source']['title']) . "</p>";
        }
    } else {
        // Szukanie w MySQL
        echo "<p><em>Brak wyników w Elasticsearch, sprawdzam w MySQL...</em></p>";

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
    }
} else {
?>
<form>
    <input name="q" placeholder="Szukaj tytułu..." />
    <button type="submit">Szukaj</button>
</form>
<?php } ?>
