<?php
$db = new SQLite3('writable/db/operateur.db');
foreach (['client', 'prefixe', 'operateur', 'configuration', 'historique'] as $t) {
    echo $t . ': ' . $db->querySingle('SELECT COUNT(*) FROM "' . $t . '"') . "\n";
}
