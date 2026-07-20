PRAGMA foreign_keys = ON;

CREATE TABLE client (
    id INTEGER PRIMARY KEY,
    nom TEXT NOT NULL,
    code TEXT NOT NULL,
    numero TEXT NOT NULL
);

CREATE TABLE type_operation (
    id INTEGER PRIMARY KEY,
    libelle TEXT NOT NULL
);

CREATE TABLE configuration (
    id INTEGER PRIMARY KEY,
    min REAL NOT NULL,
    max REAL NOT NULL,
    id_type_operation INTEGER NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

CREATE TABLE operateur (
    id INTEGER PRIMARY KEY,
    nom TEXT NOT NULL,
    mdp TEXT NOT NULL
);

CREATE TABLE historique (
    id INTEGER PRIMARY KEY,
    id_client INTEGER NOT NULL,
    id_type_operation INTEGER NOT NULL,
    montant REAL NOT NULL,
    date TEXT DEFAULT CURRENT_TIMESTAMP,
    frais REAL NOT NULL,
    FOREIGN KEY (id_client) REFERENCES client(id),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);