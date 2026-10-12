<?php

declare(strict_types=1);

require_once __DIR__ . '/../connexion.php';
require_once __DIR__ . '/../Produit.php';

$pdo = creerConnexionPDO();
$requete = $pdo->query('SELECT id, nom, prix, stock FROM produit WHERE id = 1');
$ligne = $requete->fetch(PDO::FETCH_ASSOC);

// Hydratation manuelle : les valeurs PDO sont converties dans les bons types.
$produit = $ligne === false ? null : new Produit(
    (int) $ligne['id'],
    (string) $ligne['nom'],
    (float) $ligne['prix'],
    (int) $ligne['stock']
);
?>
<!doctype html>
<html lang="fr"><meta charset="utf-8"><title>Exercice 3</title>
<body>
    <h1>Exercice 3 — Ligne SQL vers objet</h1>
    <pre><?php if ($produit !== null) { $produit->afficher(); } else { echo 'Produit introuvable.'; } ?></pre>
</body>
</html>
