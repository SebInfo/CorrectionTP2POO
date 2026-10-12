<?php

declare(strict_types=1);

require_once __DIR__ . '/../connexion.php';
require_once __DIR__ . '/../Produit.php';

class ProduitRepositoryExercice6
{
    public function __construct(private PDO $pdo)
    {
    }

    private function hydrater(array $ligne): Produit
    {
        return new Produit((int) $ligne['id'], (string) $ligne['nom'], (float) $ligne['prix'], (int) $ligne['stock']);
    }

    /** @return Produit[] */
    public function findByNom(string $recherche): array
    {
        $requete = $this->pdo->prepare(
            'SELECT id, nom, prix, stock FROM produit WHERE nom LIKE :recherche ORDER BY id'
        );
        // Les % sont ajoutés à la valeur, pas dans le texte SQL.
        $requete->execute(['recherche' => '%' . $recherche . '%']);

        $produits = [];
        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $ligne) {
            $produits[] = $this->hydrater($ligne);
        }

        return $produits;
    }
}

$recherche = trim((string) ($_GET['recherche'] ?? 'clavier'));
$repository = new ProduitRepositoryExercice6(creerConnexionPDO());
$produits = $repository->findByNom($recherche);
?>
<!doctype html>
<html lang="fr"><meta charset="utf-8"><title>Exercice 6</title>
<body>
    <h1>Exercice 6 — Recherche</h1>
    <form method="get"><input name="recherche" value="<?= htmlspecialchars($recherche) ?>"><button>Rechercher</button></form>
    <pre><?php foreach ($produits as $produit) { $produit->afficher(); } ?></pre>
    <?php if ($produits === []): ?><p>Aucun résultat.</p><?php endif; ?>
</body>
</html>
