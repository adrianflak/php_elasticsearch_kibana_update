<?php
ini_set("log_errors", 1);
//ini_set("error_log", "/var/www/cw_1/logs/app.log");
ini_set("error_log", "/var/www/php_elasticsearch_kibana/logs/app.log");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'elastic.php';

$query = $_GET['q'] ?? '';

if ($query) {
    $results = $client->search([
        'index' => 'articles',
        'body'  => [
            'query' => [
                'match' => ['title' => $query]
            ]
        ]
    ]);

    foreach ($results->asArray()['hits']['hits'] as $hit) {
        echo "<p>" . htmlspecialchars($hit['_source']['title']) . "</p>";
    }
} else {
?>
<form>
    <input name="q" placeholder="Szukaj tytułu..." />
    <button type="submit">Szukaj</button>
</form>
<?php } ?>
