<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

/**
 * Génère des données de test réalistes pour le projet Opérateur :
 * préfixes, clients (répartis entre les opérateurs existants),
 * configurations (tranches de frais) et un historique de transactions
 * (dépôts, retraits, transferts intra/inter-opérateurs avec commission).
 *
 * Ce seeder est rejouable : il évite de créer des doublons si des
 * données similaires existent déjà (par numéro, code, min/max, etc.).
 *
 * Utilisation :
 *   php spark db:seed TestDataSeeder
 */
class TestDataSeeder extends Seeder
{
    public function run()
    {
        $typeOperations   = $this->getTypeOperationIds();
        $operateurIds     = $this->getOperateurIds();
        $operateurIdsByNom = $this->getOperateurIdsByNom();

        $this->seedPrefixes($operateurIdsByNom);
        $clientIds = $this->seedClients($operateurIds);
        $this->seedConfigurations($typeOperations);
        $this->seedHistorique($clientIds, $typeOperations);

        CLI::write('Données de test générées avec succès.', 'green');
    }

    /**
     * Récupère les id des types d'opération existants (depot, retrait, transfert).
     * On ne crée jamais de nouveaux types : leurs id sont utilisés en dur
     * ailleurs dans l'application (ClientController::formulaire).
     */
    private function getTypeOperationIds(): array
    {
        $rows = $this->db->table('type_operation')->get()->getResultArray();

        $ids = [];
        foreach ($rows as $row) {
            $ids[$row['libelle']] = (int) $row['id'];
        }

        return $ids;
    }

    /**
     * @return list<int> id de tous les opérateurs existants, triés par id.
     */
    private function getOperateurIds(): array
    {
        $rows = $this->db->table('operateur')->orderBy('id', 'ASC')->get()->getResultArray();

        return array_map('intval', array_column($rows, 'id'));
    }

    /**
     * @return array<string,int> id des opérateurs indexés par leur nom.
     */
    private function getOperateurIdsByNom(): array
    {
        $rows = $this->db->table('operateur')->get()->getResultArray();

        $ids = [];
        foreach ($rows as $row) {
            $ids[$row['nom']] = (int) $row['id'];
        }

        return $ids;
    }

    /**
     * Chaque préfixe appartient à un opérateur. Seuls les numéros dont le
     * préfixe appartient à l'opérateur principal ("My Op", id 1) peuvent se
     * connecter en tant que client (voir LoginController::validation()).
     */
    private function seedPrefixes(array $operateurIdsByNom): void
    {
        if (empty($operateurIdsByNom)) {
            return;
        }

        $existing = array_column($this->db->table('prefixe')->get()->getResultArray(), 'code');

        $prefixes = [
            '034' => 'My Op',
            '033' => 'Telma',
            '038' => 'Telma',
            '032' => 'Orange',
        ];

        $premierOperateurId = $operateurIdsByNom[array_key_first($operateurIdsByNom)];

        foreach ($prefixes as $code => $nom) {
            if (in_array($code, $existing, true)) {
                continue;
            }

            $operateurId = $operateurIdsByNom[$nom] ?? $premierOperateurId;

            $this->db->table('prefixe')->insert([
                'code'         => $code,
                'operateur_id' => $operateurId,
            ]);
        }
    }

    /**
     * @return list<int> Les id de tous les clients de test (nouveaux + déjà existants)
     */
    private function seedClients(array $operateurIds): array
    {
        $existing        = $this->db->table('client')->get()->getResultArray();
        $existingNumeros = array_column($existing, 'numero');
        $ids             = array_map('intval', array_column($existing, 'id'));

        if (empty($operateurIds)) {
            // Pas d'opérateur en base : impossible de créer des clients
            // (operateur_id est obligatoire).
            return $ids;
        }

        // Numéros alignés sur le préfixe de l'opérateur assigné en round-robin
        // ci-dessous (034 = My Op, 033/038 = Telma, 032 = Orange), pour que
        // les données restent cohérentes avec la règle de connexion client.
        $clients = [
            ['nom' => 'Alice Randria',              'code' => 'CL1001', 'numero' => '0341234501'],
            ['nom' => 'Bakoly Rasoanaivo',           'code' => 'CL1002', 'numero' => '0331234502'],
            ['nom' => 'Claude Andriamampionona',     'code' => 'CL1003', 'numero' => '0321234503'],
            ['nom' => 'Domoina Rabemananjara',       'code' => 'CL1004', 'numero' => '0341234504'],
            ['nom' => 'Eric Rakotomalala',           'code' => 'CL1005', 'numero' => '0381234505'],
            ['nom' => 'Fara Ravalisonirina',         'code' => 'CL1006', 'numero' => '0321234506'],
            ['nom' => 'Gaston Andriantsitohaina',    'code' => 'CL1007', 'numero' => '0341234507'],
            ['nom' => 'Hanta Rasolofomanana',        'code' => 'CL1008', 'numero' => '0331234508'],
        ];

        foreach ($clients as $i => $client) {
            if (in_array($client['numero'], $existingNumeros, true)) {
                continue;
            }

            // On répartit les clients entre les opérateurs existants
            // (round-robin), pour pouvoir tester les transferts
            // intra-opérateur et inter-opérateur (avec commission).
            $client['operateur_id'] = $operateurIds[$i % count($operateurIds)];

            $this->db->table('client')->insert($client);
            $ids[] = (int) $this->db->insertID();
        }

        return $ids;
    }

    private function seedConfigurations(array $typeOperations): void
    {
        // Tranches de frais par type d'opération : [min, max, montant du frais]
        $tranches = [
            'depot' => [
                [1, 50, 5],
                [51, 200, 10],
                [201, 1000, 20],
                [1001, 5000, 50],
            ],
            'retrait' => [
                [1, 50, 6],
                [51, 200, 12],
                [201, 1000, 25],
                [1001, 5000, 60],
            ],
            'transfert' => [
                [1, 50, 7],
                [51, 200, 15],
                [201, 1000, 30],
                [1001, 5000, 70],
            ],
        ];

        $existing      = $this->db->table('configuration')->get()->getResultArray();
        $existingKeys = [];
        foreach ($existing as $row) {
            $key                = $row['id_type_operation'] . ':' . $row['min'] . ':' . $row['max'];
            $existingKeys[$key] = true;
        }

        foreach ($tranches as $libelle => $rows) {
            if (!isset($typeOperations[$libelle])) {
                continue;
            }

            $idTypeOperation = $typeOperations[$libelle];

            foreach ($rows as [$min, $max, $montant]) {
                $key = $idTypeOperation . ':' . $min . ':' . $max;

                if (isset($existingKeys[$key])) {
                    continue;
                }

                $this->db->table('configuration')->insert([
                    'id_type_operation' => $idTypeOperation,
                    'min'               => $min,
                    'max'               => $max,
                    'montant'           => $montant,
                ]);
            }
        }
    }

    /**
     * Renvoie le frais configuré pour un montant et un type d'opération donnés
     * (même logique que HistoriqueModel::getFrais()).
     */
    private function getFrais(int $idTypeOperation, float $montant): float
    {
        $row = $this->db->table('configuration')
            ->where('id_type_operation', $idTypeOperation)
            ->where('min <=', $montant)
            ->where('max >=', $montant)
            ->get()
            ->getRowArray();

        return $row ? (float) $row['montant'] : 0.0;
    }

    private function insertHistorique(?int $idClient, ?int $idDestinataire, ?int $idTypeOperation, float $montant, float $frais, float $commission, int $joursAgo): void
    {
        if ($idClient === null || $idTypeOperation === null) {
            return;
        }

        $this->db->table('historique')->insert([
            'id_client'         => $idClient,
            'id_destinataire'   => $idDestinataire,
            'id_type_operation' => $idTypeOperation,
            'montant'           => $montant,
            'frais'             => $frais,
            'commission'        => $commission,
            'date'              => date('Y-m-d H:i:s', strtotime('-' . $joursAgo . ' days')),
        ]);
    }

    private function seedHistorique(array $clientIds, array $typeOperations): void
    {
        if (count($clientIds) < 8 || empty($typeOperations)) {
            return;
        }

        // On ne rajoute de l'historique que si la table est encore peu peuplée,
        // pour éviter de la faire grossir indéfiniment à chaque exécution.
        if ($this->db->table('historique')->countAllResults() >= 16) {
            return;
        }

        $depot     = $typeOperations['depot'] ?? null;
        $retrait   = $typeOperations['retrait'] ?? null;
        $transfert = $typeOperations['transfert'] ?? null;

        // Opérateur de chaque client, pour savoir si un transfert est
        // inter-opérateurs (et doit donc supporter une commission).
        $clientOperateurs = [];
        foreach ($this->db->table('client')->get()->getResultArray() as $c) {
            $clientOperateurs[(int) $c['id']] = (int) $c['operateur_id'];
        }

        // Dépôts / retraits variés sur les 4 premiers clients de test
        $operations = [
            ['client' => $clientIds[0], 'type' => $depot,   'montant' => 45,   'jours' => 10],
            ['client' => $clientIds[0], 'type' => $retrait, 'montant' => 30,   'jours' => 8],
            ['client' => $clientIds[1], 'type' => $depot,   'montant' => 150,  'jours' => 9],
            ['client' => $clientIds[1], 'type' => $retrait, 'montant' => 80,   'jours' => 6],
            ['client' => $clientIds[2], 'type' => $depot,   'montant' => 500,  'jours' => 7],
            ['client' => $clientIds[2], 'type' => $retrait, 'montant' => 300,  'jours' => 4],
            ['client' => $clientIds[3], 'type' => $depot,   'montant' => 2000, 'jours' => 5],
            ['client' => $clientIds[3], 'type' => $retrait, 'montant' => 40,   'jours' => 3],
        ];

        foreach ($operations as $op) {
            if ($op['type'] === null) {
                continue;
            }

            $frais = $this->getFrais($op['type'], (float) $op['montant']);
            $this->insertHistorique($op['client'], null, $op['type'], (float) $op['montant'], $frais, 0, $op['jours']);
        }

        // Transferts entre clients de test : certains intra-opérateur
        // (commission = 0), d'autres inter-opérateurs (commission = 10 %).
        if ($transfert !== null && $depot !== null) {
            $transferts = [
                // Intra-opérateur (même operateur_id) : pas de commission
                ['from' => $clientIds[0], 'to' => $clientIds[3], 'montant' => 20,  'jours' => 2],
                // Inter-opérateurs : commission de 10 %
                ['from' => $clientIds[4], 'to' => $clientIds[5], 'montant' => 100, 'jours' => 6],
                ['from' => $clientIds[5], 'to' => $clientIds[6], 'montant' => 60,  'jours' => 3],
                ['from' => $clientIds[6], 'to' => $clientIds[7], 'montant' => 400, 'jours' => 1],
            ];

            foreach ($transferts as $t) {
                $frais = $this->getFrais($transfert, (float) $t['montant']);

                $operateurFrom = $clientOperateurs[$t['from']] ?? null;
                $operateurTo   = $clientOperateurs[$t['to']] ?? null;

                $isInterOperateur = $operateurFrom !== null
                    && $operateurTo !== null
                    && $operateurFrom !== $operateurTo;

                $commission = $isInterOperateur ? round($t['montant'] * 0.1, 2) : 0;

                // Débit chez l'expéditeur
                $this->insertHistorique($t['from'], $t['to'], $transfert, (float) $t['montant'], $frais, $commission, $t['jours']);

                // Crédit chez le destinataire (comme HistoriqueModel::recus, sans frais ni commission)
                $this->insertHistorique($t['to'], null, $depot, (float) $t['montant'], 0, 0, $t['jours']);
            }
        }
    }
}
