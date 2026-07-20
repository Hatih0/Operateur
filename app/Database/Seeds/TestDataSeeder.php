<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

/**
 * Génère des données de test réalistes pour le projet Opérateur :
 * préfixes, clients, opérateurs, configurations (tranches de frais)
 * et un historique de transactions.
 *
 * Ce seeder est rejouable : il évite de créer des doublons si des
 * données similaires existent déjà (par numéro, code, nom, etc.).
 *
 * Utilisation :
 *   php spark db:seed TestDataSeeder
 */
class TestDataSeeder extends Seeder
{
    public function run()
    {
        $typeOperations = $this->getTypeOperationIds();

        $this->seedPrefixes();
        $clientIds = $this->seedClients();
        $this->seedOperateurs();
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

    private function seedPrefixes(): void
    {
        $existing = array_column($this->db->table('prefixe')->get()->getResultArray(), 'code');

        $prefixes = ['032', '033', '034', '038'];

        foreach ($prefixes as $code) {
            if (in_array($code, $existing, true)) {
                continue;
            }

            $this->db->table('prefixe')->insert(['code' => $code]);
        }
    }

    /**
     * @return list<int> Les id de tous les clients de test (nouveaux + déjà existants)
     */
    private function seedClients(): array
    {
        $clients = [
            ['nom' => 'Alice Randria',              'code' => 'CL1001', 'numero' => '0321234501'],
            ['nom' => 'Bakoly Rasoanaivo',           'code' => 'CL1002', 'numero' => '0331234502'],
            ['nom' => 'Claude Andriamampionona',     'code' => 'CL1003', 'numero' => '0341234503'],
            ['nom' => 'Domoina Rabemananjara',       'code' => 'CL1004', 'numero' => '0381234504'],
            ['nom' => 'Eric Rakotomalala',           'code' => 'CL1005', 'numero' => '0321234505'],
            ['nom' => 'Fara Ravalisonirina',         'code' => 'CL1006', 'numero' => '0331234506'],
            ['nom' => 'Gaston Andriantsitohaina',    'code' => 'CL1007', 'numero' => '0341234507'],
            ['nom' => 'Hanta Rasolofomanana',        'code' => 'CL1008', 'numero' => '0381234508'],
        ];

        $existing = $this->db->table('client')->get()->getResultArray();
        $existingNumeros = array_column($existing, 'numero');
        $ids = array_map('intval', array_column($existing, 'id'));

        foreach ($clients as $client) {
            if (in_array($client['numero'], $existingNumeros, true)) {
                continue;
            }

            $this->db->table('client')->insert($client);
            $ids[] = (int) $this->db->insertID();
        }

        return $ids;
    }

    private function seedOperateurs(): void
    {
        $operateurs = [
            ['nom' => 'admin', 'mdp' => 'admin123'],
            ['nom' => 'marie', 'mdp' => 'marie2024'],
        ];

        $existing = array_column($this->db->table('operateur')->get()->getResultArray(), 'nom');

        foreach ($operateurs as $operateur) {
            if (in_array($operateur['nom'], $existing, true)) {
                continue;
            }

            $this->db->table('operateur')->insert($operateur);
        }
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

        $existing = $this->db->table('configuration')->get()->getResultArray();
        $existingKeys = [];
        foreach ($existing as $row) {
            $key = $row['id_type_operation'] . ':' . $row['min'] . ':' . $row['max'];
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

    private function seedHistorique(array $clientIds, array $typeOperations): void
    {
        if (empty($clientIds) || empty($typeOperations)) {
            return;
        }

        // On ne rajoute de l'historique que si la table est encore peu peuplée,
        // pour éviter de la faire grossir indéfiniment à chaque exécution.
        $count = $this->db->table('historique')->countAllResults();

        if ($count >= 15) {
            return;
        }

        $depot     = $typeOperations['depot'] ?? null;
        $retrait   = $typeOperations['retrait'] ?? null;
        $transfert = $typeOperations['transfert'] ?? null;

        $operations = [];

        // Quelques dépôts et retraits classiques pour les premiers clients de test
        $operations[] = ['id_client' => $clientIds[0], 'id_type_operation' => $depot,   'montant' => 45,  'frais' => 5,  'jours' => 10];
        $operations[] = ['id_client' => $clientIds[0], 'id_type_operation' => $retrait, 'montant' => 30,  'frais' => 6,  'jours' => 8];
        $operations[] = ['id_client' => $clientIds[1], 'id_type_operation' => $depot,   'montant' => 150, 'frais' => 10, 'jours' => 9];
        $operations[] = ['id_client' => $clientIds[1], 'id_type_operation' => $retrait, 'montant' => 80,  'frais' => 12, 'jours' => 6];
        $operations[] = ['id_client' => $clientIds[2], 'id_type_operation' => $depot,   'montant' => 500, 'frais' => 20, 'jours' => 7];
        $operations[] = ['id_client' => $clientIds[2], 'id_type_operation' => $retrait, 'montant' => 300, 'frais' => 25, 'jours' => 4];
        $operations[] = ['id_client' => $clientIds[3], 'id_type_operation' => $depot,   'montant' => 2000, 'frais' => 50, 'jours' => 5];
        $operations[] = ['id_client' => $clientIds[3], 'id_type_operation' => $retrait, 'montant' => 40,  'frais' => 6,  'jours' => 3];

        foreach ($operations as $op) {
            if ($op['id_type_operation'] === null) {
                continue;
            }

            $this->db->table('historique')->insert([
                'id_client'         => $op['id_client'],
                'id_destinataire'   => null,
                'id_type_operation' => $op['id_type_operation'],
                'montant'           => $op['montant'] - $op['frais'],
                'frais'             => $op['frais'],
                'date'              => date('Y-m-d H:i:s', strtotime('-' . $op['jours'] . ' days')),
            ]);
        }

        // Quelques transferts entre clients de test
        if ($transfert !== null && count($clientIds) >= 5) {
            $transferts = [
                ['from' => $clientIds[4], 'to' => $clientIds[5], 'montant' => 100, 'frais' => 15, 'jours' => 6],
                ['from' => $clientIds[5], 'to' => $clientIds[6], 'montant' => 60,  'frais' => 7,  'jours' => 3],
                ['from' => $clientIds[6], 'to' => $clientIds[7], 'montant' => 400, 'frais' => 30, 'jours' => 1],
            ];

            foreach ($transferts as $t) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $t['jours'] . ' days'));

                // Débit chez l'expéditeur (même logique que HistoriqueModel::transfert)
                $this->db->table('historique')->insert([
                    'id_client'         => $t['from'],
                    'id_destinataire'   => $t['to'],
                    'id_type_operation' => $transfert,
                    'montant'           => $t['montant'] - $t['frais'],
                    'frais'             => $t['frais'],
                    'date'              => $date,
                ]);

                // Crédit chez le destinataire (comme dans HistoriqueModel::recus, sans frais)
                if ($depot !== null) {
                    $this->db->table('historique')->insert([
                        'id_client'         => $t['to'],
                        'id_destinataire'   => null,
                        'id_type_operation' => $depot,
                        'montant'           => $t['montant'],
                        'frais'             => 0,
                        'date'              => $date,
                    ]);
                }
            }
        }
    }
}
