<?php

declare(strict_types=1);

require_once __DIR__ . '/../connexion.php';
require_once __DIR__ . '/../Produit.php';

class ProduitRepositoryExercice7
{
    public function __construct(private PDO $pdo)
    {
    }

    public function insert(Produit $produit): void
    {
        $requete = $this->pdo->prepare(
            'INSERT INTO produit (nom, prix, stock) VALUES (:nom, :prix, :stock)'
        );
        $requete->execute([
            'nom' => $produit->getNom(),
            'prix' => $produit->getPrix(),
            'stock' => $produit->getStock(),
        ]);

        // MySQL vient de générer l'id AUTO_INCREMENT.
        $produit->setId((int) $this->pdo->lastInsertId());
    }
}

$produit = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produit = new Produit(null, 'Disque SSD 1 To', 89.90, 7);
    (new ProduitRepositoryExercice7(creerConnexionPDO()))->insert($produit);
}
?>
<!doctype html>
<html lang="fr"><meta charset="utf-8"><title>Exercice 7</title>
<body>
    <h1>Exercice 7 — Insertion</h1>
    <form method="post"><button>Insérer « Disque SSD 1 To »</button></form>
    <?php if ($produit !== null): ?><p>Produit inséré avec l'id <?= $produit->getId() ?>.</p><?php endif; ?>
</body>
</html>
