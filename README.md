# Correction — TP POO PHP et PDO

Cette correction est volontairement simple : elle vise des étudiants de BTS SIO
SLAM en début de deuxième année. Chaque responsabilité est placée dans un
fichier différent :

```text
TP2POO/
├── connexion.php           # création de PDO
├── Produit.php             # objet métier et validations
├── ProduitRepository.php   # toutes les requêtes SQL et l'hydratation
├── index.php               # affichage et traitement des formulaires
└── database/boutique.sql   # base et données de départ
```

## Installation

1. Importer `database/boutique.sql` dans phpMyAdmin.
2. Adapter la connexion si besoin. Par défaut, `connexion.php` utilise
   `localhost`, le port `3306`, l'utilisateur `root` et un mot de passe vide,
   comme dans l'énoncé. Pour MAMP, les valeurs fréquentes sont le port `8889`
   et le mot de passe `root`.
3. Ouvrir `http://localhost:8888/TP2POO/index.php` (adapter le port Apache de
   MAMP si nécessaire).

## Corrections séparées

Les corrections des exercices 1 à 9 sont disponibles une par fichier dans le
dossier [`exercices`](exercices/README.md). Elles sont progressives et peuvent
être lancées individuellement. Ce dossier est le point de départ conseillé pour
la correction avec les étudiants.

Le code à la racine correspond à l'exercice 10 (synthèse) : il rassemble les
classes finales et la page du catalogue.

## Lien avec les exercices

- Exercices 1 et 3 : `findById()` récupère une ligne avec `fetch()` puis la
  transforme en objet.
- Exercice 2 : `Produit.php` contient attributs privés, constructeur, getters,
  setters et `afficher()`.
- Exercices 4 à 6 : le Repository contient `findById`, `findAll`,
  `findByNom` et la méthode privée `hydrater`.
- Exercices 7 à 9 : `insert`, `update` et `delete` réalisent le CRUD. La page
  permet de tester l'ajout et la suppression.
- Exercice 10 : `index.php` affiche, recherche et ajoute des produits sans
  aucune requête SQL dans la page.

## Réponses courtes aux questions

1. Avec `PDO::FETCH_ASSOC`, `fetch()` retourne un tableau associatif, ou
   `false` quand aucune ligne n'est trouvée.
2. Remplir un objet depuis une ligne SQL s'appelle **l'hydratation**.
3. `hydrater()` évite de recopier les conversions de types dans chaque méthode
   du Repository et garantit que tous les produits sont construits de la même
   façon.
4. Avant l'insertion, l'identifiant vaut `null` car il est généré par MySQL
   lors du `INSERT`. `lastInsertId()` permet ensuite de le placer dans l'objet.
5. Modifier l'objet avec `setPrix()` ne modifie que la mémoire PHP. Il faut
   appeler `update()` pour envoyer une requête `UPDATE` à MySQL.
6. `"DELETE ... " . $_GET['id']` est dangereux : une valeur saisie peut
   modifier la requête SQL (injection SQL). Une requête préparée sépare le SQL
   des données et protège la valeur transmise.
7. `Produit` représente les données et les règles métier ;
   `ProduitRepository` s'occupe de les enregistrer et lire ; PDO est l'outil
   qui permet à PHP de dialoguer avec MySQL. Une ligne de `produit` correspond
   à un objet `Produit` : c'est une forme simple de mapping objet-relationnel
   (ORM manuel).
