<?php

declare(strict_types=1);

require_once __DIR__ . '/../connexion.php';
require_once __DIR__ . '/../Produit.php';

class ProduitRepositoryExercice4
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findById(int $id): ?Produit
    {
        // La donnée variable n'est jamais concaténée dans le SQL.
        $requete = $this->pdo->prepare(
            'SELECT id, nom, prix, stock FROM produit WHERE id = :id'
        );
        $requete->execute(['id' => $id]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);

        if ($ligne === false) {
            return null;
        }

        return new Produit(
            (int) $ligne['id'],
            (string) $ligne['nom'],
            (float) $ligne['prix'],
            (int) $ligne['stock']
        );
    }
}

$repository = new ProduitRepositoryExercice4(creerConnexionPDO());
$produit = $repository->findById(2);
?>
<!doctype html>
<html lang="fr"><meta charset="utf-8"><title>Exercice 4</title>
<body><h1>Exercice 4 — Repository</h1><pre><?php if ($produit) { $produit->afficher(); } else { echo 'Produit introuvable.'; } ?></pre></body>
</html>
