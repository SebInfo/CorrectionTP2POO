<?php

declare(strict_types=1);

require_once __DIR__ . '/../connexion.php';
require_once __DIR__ . '/../Produit.php';

class ProduitRepositoryExercice5
{
    public function __construct(private PDO $pdo)
    {
    }

    // Une seule méthode de conversion : pas de code dupliqué dans findById/findAll.
    private function hydrater(array $ligne): Produit
    {
        return new Produit(
            (int) $ligne['id'],
            (string) $ligne['nom'],
            (float) $ligne['prix'],
            (int) $ligne['stock']
        );
    }

    public function findById(int $id): ?Produit
    {
        $requete = $this->pdo->prepare(
            'SELECT id, nom, prix, stock FROM produit WHERE id = :id'
        );
        $requete->execute(['id' => $id]);
        $ligne = $requete->fetch(PDO::FETCH_ASSOC);

        return $ligne === false ? null : $this->hydrater($ligne);
    }

    /** @return Produit[] */
    public function findAll(): array
    {
        $requete = $this->pdo->query(
            'SELECT id, nom, prix, stock FROM produit ORDER BY id'
        );
        $produits = [];

        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $ligne) {
            $produits[] = $this->hydrater($ligne);
        }

        return $produits;
    }
}

$repository = new ProduitRepositoryExercice5(creerConnexionPDO());
$produits = $repository->findAll();
?>
<!doctype html>
<html lang="fr"><meta charset="utf-8"><title>Exercice 5</title>
<body>
    <h1>Exercice 5 — Hydratation et findAll()</h1>
    <pre><?php foreach ($produits as $produit) { $produit->afficher(); } ?></pre>
</body>
</html>
