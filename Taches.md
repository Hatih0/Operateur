# Projet Gestion Opérateur / Client

## 1 - Configuration de base

* [x] **Gestion des préfixes (CRUD)** — *Fitahiana*

  * [x] Créer `PrefixeController`
  * [x] Créer `PrefixeModel`
  * [x] Créer les vues :

    * `Prefixe/ajouter`
    * `Prefixe/modifier`

---

* [x] **Gestion Type Opération et Configuration (CRUD)** — *Fitahiana*

  * [x] Créer `TypeOperationController`
  * [x] Créer `TypeOperationModel`
  * [x] Créer `ConfigurationController`
  * [x] Créer `ConfigurationModel`
  * [x] Créer les vues :

    * `TypeOperation/ajouter`
    * `TypeOperation/modifier`
    * `Configuration/ajouter`
    * `Configuration/modifier`

---

# 2 - Partie Opérateur

## Authentification opérateur — *Herman*

* [X] **Login opérateur**

  * [X] Création de la gestion de connexion opérateur
  * [X] Vérification des identifiants
  * [X] Gestion de session opérateur

---

## Situation des gains — *Herman*

* [X] **Calcul des gains par type d'opération**

  * [X] `TypeOperationModel`

    * [X] Fonction `getGainParType($id_type_operation, $date)`
    * [X] Fonction `getGainTotal($date)`

* [X] **Affichage de la situation des gains**

  * [X] `TypeOperationController`

    * [X] Fonction `getSituationGain()`
    * [X] Récupération date POST avec date actuelle par défaut
    * [X] Calcul :

      * [X] Gain total et nombre total d'opérations
      * [X] Gain/nombre par type d'opération
  * [X] Vue `operateur/gain.php`

    * [X] Affichage gain global
    * [X] Affichage gain par type

---

## Situation des comptes clients — *Herman*

* [X] **Gestion des clients**

  * [X] Créer `ClientModel`

    * [X] Fonction `getAllClients()`
    * [X] Fonction `getClientById($id)`
    * [X] Fonction `getSituationClient($id)`

* [X] **Affichage des comptes clients**

  * [X] Créer `ClientController`

    * [X] Fonction `getAllComptesClients()`
    * [X] Fonction `getSituationClient($id)`
  * [X] Vue `operateur/allSituationClients.php`

    * [X] Liste des clients
    * [X] Lien vers la situation d'un client
  * [X] Vue `operateur/situationClient.php`

    * [X] Informations client
    * [X] Solde du compte

---

# 3 - Partie Client

## Consultation du compte client — *Herman*

* [X] **Voir le solde et historique**

  * [X] `ClientModel`

    * [X] Fonction `getHistoriqueClient($id)`

* [X] **ClientController**

  * [X] Fonction `situation($id)`

    * [X] Récupérer les informations client
    * [X] Récupérer le solde
    * [X] Récupérer l'historique

* [X] **Vue `client/situation.php`**

  * [X] Afficher le solde global
  * [X] Afficher les informations personnelles
  * [X] Afficher l'historique des transactions trié par date décroissante
  * [X] Ajouter les boutons :

    * [X] Faire un dépôt
    * [X] Faire un retrait
    * [X] Faire un transfert

---

## Effectuer une opération — *Herman*

* [X] **Formulaire générique d'opération**

  * [X] Vue `client/formulaire.php`

    * [X] Champ montant
    * [X] Champ code client
    * [X] Champ numéro client
    * [X] Champ numéro destinataire pour transfert
    * [X] Champ caché `id_type_operation`
    * [X] Bouton validation

* [X] **Gestion des transactions**

  * [X] Créer `HistoriqueModel`

    * [X] Fonction `depot()`
    * [X] Fonction `retrait()`
    * [X] Fonction `transfert()`
    * [X] Calcul des frais selon la configuration
    * [X] Enregistrement dans l'historique

---

# Résumé des responsabilités

| Partie                            | Responsable |
| --------------------------------- | ----------- |
| Préfixe CRUD                      | Fitahiana   |
| Type opération CRUD               | Fitahiana   |
| Configuration CRUD                | Fitahiana   |
| Login opérateur                   | Herman      |
| Situation gains opérateur         | Herman      |
| Gestion comptes clients opérateur | Herman      |
| Consultation solde client         | Herman      |
| Dépôt / retrait / transfert       | Herman      |
