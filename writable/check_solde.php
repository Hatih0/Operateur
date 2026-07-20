<?php
$db = new SQLite3('writable/db/operateur.db');

foreach ([1, 2] as $id) {
    $res = $db->query("
        SELECT
            SUM(CASE WHEN t.libelle = 'depot' THEN h.montant ELSE 0 END) AS depot,
            SUM(CASE WHEN t.libelle IN ('transfert','retrait') THEN h.montant ELSE 0 END) AS sortie
        FROM historique h
        JOIN type_operation t ON t.id = h.id_type_operation
        WHERE h.id_client = $id
    ");
    $row = $res->fetchArray(SQLITE3_ASSOC);
    $depot = $row['depot'] ?? 0;
    $sortie = $row['sortie'] ?? 0;

    $recuRes = $db->query("SELECT SUM(montant) AS recu FROM historique WHERE id_destinataire = $id");
    $recuRow = $recuRes->fetchArray(SQLITE3_ASSOC);
    $recu = $recuRow['recu'] ?? 0;

    echo "client $id -> depot=$depot sortie=$sortie recu(bug)=$recu | ANCIEN solde = " . ($depot + $sortie + $recu) . " | NOUVEAU solde (corrige) = " . ($depot + $sortie) . "\n";
}
