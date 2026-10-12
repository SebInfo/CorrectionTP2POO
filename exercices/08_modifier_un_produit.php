<?php

declare(strict_types=1);

require_once __DIR__ . '/../connexion.php';
require_once __DIR__ . '/../Produit.php';

class ProduitRepositoryExercice8
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findById(int $id): ?Produit
    {
        $requete = $this->pdo->prepare('SELECT id, nom, prix, stock FROM produit WHERE id = :id');
        $requete->execute(['id' => $id]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);

        return $ligne === false ? null : new Produit((int) $ligne['id'], (string) $ligne['nom'], (float) $ligne['prix'], (int) $ligne['stock']);
    }

    public function update(Produit $produit): void
    {
        if ($produit->getId() === null) {
            throw new LogicException('Un produit à modifier doit avoir un id.');
        }

        $requete = $this->pdo->prepare(
            'UPDATE produit SET nom = :nom, prix = :prix, stock = :stock WHERE id = :id'
        );
        $requete->execute([
            'id' => $produit->getId(), 'nom' => $produit->getNom(),
            'prix' => $produit->getPrix(), 'stock' => $produit->getStock(),
        ]);
    }
}

$repository = new ProduitRepositoryExercice8(creerConnexionPDO());
$produit = $repository->findById(1);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $produit !== null) {
    // Les setters modifient l'objet en mémoire ; update() enregistre dans MySQL.
    $produit->setPrix(74.90);
    $produit->setStock(20);
    $repository->update($produit);
    $produit = $repository->findById(1); // vérification : relecture depuis la base
}
?>
<!doctype html>
<html lang="fr"><meta charset="utf-8"><title>Exercice 8</title>
<body>
    <h1>Exercice 8 — Modification</h1>
    <form method="post"><button>Passer le produit 1 à 74,90 € et 20 en stock</button></form>
    <pre><?php if ($produit) { $produit->afficher(); } else { echo 'Produit 1 introuvable.'; } ?></pre>
</body>
</html>
