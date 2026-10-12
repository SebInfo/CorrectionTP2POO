<?php

declare(strict_types=1);

require_once __DIR__ . '/../connexion.php';

$pdo = creerConnexionPDO();
$requete = $pdo->query('SELECT id, nom, prix, stock FROM produit WHERE id = 1');

// FETCH_ASSOC donne un tableau associatif : ['id' => ..., 'nom' => ..., etc.]
$produit = $requete->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr"><meta charset="utf-8"><title>Exercice 1</title>
<body>
    <h1>Exercice 1 — Lire une ligne avec PDO</h1>
    <?php if ($produit === false): ?>
        <p>Le produit d'identifiant 1 n'existe pas.</p>
    <?php else: ?>
        <p>Produit : <?= htmlspecialchars($produit['nom']) ?></p>
        <p>Prix : <?= htmlspecialchars($produit['prix']) ?> euros</p>
        <p>Stock : <?= htmlspecialchars($produit['stock']) ?></p>
    <?php endif; ?>
</body>
</html>
