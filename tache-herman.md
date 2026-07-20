Operateur.

Situation gain via les différents frais ( retrait et transfert) :
    - TypeOperationModel 
        - fonction getGainParType ( $id_type_operation, $date ) retournant le nombre du
        type et la somme des frais du type <= date 
        - fonction getGainTotal ( $date ) retournant le et somme des operation
        total des gains <= date en sommant les par frais par types par appel de fonction 
    - TypeOperationController
        - fonction getSituationGain () :
            recuperation de la date par post, default now.
            total des gains combines - nombres des operations totaux 
            , total des gains - nb transfert, 
            total des gains - nb depot, 
            total des gains - nb retrait
            return operateur/gain,$data
    - operateur/gain.php 
        - boite pour gain total
        - boites displayed par types        

Situation des comptes clients () :
    - creer ClientModel 
        - fonction getAllClients () ;
        - fonction getClientById ($id) ;
        - fonction getSituationClient ( $id ) pour recuperer
          la somme des montants de ses transactions (depot - (transfert+retrait))
    - creer ClientController 
        - fonction getAllComptesClients () pour retourner la liste des clients ;
        - fonction getSituationClient ( $id )pour retourner les infos des clients ;
    - operateur/allSituationClients.php pour afficher la liste avec lien href des clients
    - operateur/situationClient.php pour afficher la situation particuliere d'un compte client


Client.

Voir solde :
    - ClientModel :
        - fonction getHistoriqueClient ($id) pour retourner l'historique des transactions
    - ClientController :
        - fonction situation ($id) : 
            recuperer l'id, 
            $data = [
                'informations' => getClientById () ;
                'situation' => getSituationClient () ;
                'historique' => getHistoriqueClient () ;
            ]
            retourner la vue ('client/situation',$data);

    - client/situation.php : une boite div pour le solde global, une boite div pour les informations personnelles.
    un tableau pour la liste des historiques par date desc .

            