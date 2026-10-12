<?php

declare(strict_types=1);

require_once __DIR__ . '/connexion.php';
require_once __DIR__ . '/Produit.php';
require_once __DIR__ . '/ProduitRepository.php';

$erreur = null;
$message = $_GET['message'] ?? '';
$recherche = trim((string) ($_GET['recherche'] ?? ''));

try {
    $repository = new ProduitRepository(creerConnexionPDO());

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'ajouter') {
            $nom = trim((string) ($_POST['nom'] ?? ''));
            $prixSaisi = str_replace(',', '.', trim((string) ($_POST['prix'] ?? '')));
            $stockSaisi = trim((string) ($_POST['stock'] ?? ''));

            if (!is_numeric($prixSaisi) || filter_var($stockSaisi, FILTER_VALIDATE_INT) === false) {
                throw new InvalidArgumentException('Le prix et le stock doivent être des nombres valides.');
            }

            $produit = new Produit(null, $nom, (float) $prixSaisi, (int) $stockSaisi);
            $repository->insert($produit);

            header('Location: index.php?message=ajoute');
            exit;
        }

        if ($action === 'supprimer') {
            $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
            if ($id === false || $id <= 0) {
                throw new InvalidArgumentException('Identifiant de produit invalide.');
            }

            $repository->delete($id);
            header('Location: index.php?message=supprime');
            exit;
        }
    }

    $produits = $recherche === ''
        ? $repository->findAll()
        : $repository->findByNom($recherche);
} catch (PDOException $exception) {
    $erreur = 'La connexion à la base de données a échoué. Vérifiez connexion.php.';
    $produits = [];
} catch (InvalidArgumentException $exception) {
    $erreur = $exception->getMessage();
    $produits = isset($repository) ? $repository->findAll() : [];
}

function e(string|int|float|null $valeur): string
{
    return htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catalogue de produits</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 960px; margin: 2rem auto; padding: 0 1rem; }
        form { margin: 1rem 0; padding: 1rem; background: #f4f6f8; }
        label { display: inline-block; margin: .35rem .6rem .35rem 0; }
        input { padding: .4rem; }
        button { padding: .45rem .7rem; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #cbd5e1; padding: .6rem; text-align: left; }
        th { background: #e2e8f0; }
        .message { color: #166534; } .erreur { color: #b91c1c; }
        .inline { margin: 0; padding: 0; background: transparent; }
    </style>
</head>
<body>
    <h1>Catalogue de produits</h1>

    <?php if ($message === 'ajoute'): ?>
        <p class="message">Le produit a été ajouté.</p>
    <?php elseif ($message === 'supprime'): ?>
        <p class="message">Le produit a été supprimé.</p>
    <?php endif; ?>

    <?php if ($erreur !== null): ?>
        <p class="erreur"><?= e($erreur) ?></p>
    <?php endif; ?>

    <form method="get" action="index.php">
        <label>
            Nom du produit :
            <input type="search" name="recherche" value="<?= e($recherche) ?>">
        </label>
        <button type="submit">Rechercher</button>
        <a href="index.php">Réinitialiser</a>
    </form>

    <form method="post" action="index.php">
        <h2>Ajouter un produit</h2>
        <input type="hidden" name="action" value="ajouter">
        <label>Nom : <input name="nom" required maxlength="100"></label>
        <label>Prix : <input name="prix" required inputmode="decimal" placeholder="79,90"></label>
        <label>Stock : <input name="stock" required type="number" min="0"></label>
        <button type="submit">Ajouter</button>
    </form>

    <h2><?= $recherche === '' ? 'Tous les produits' : 'Résultats de la recherche' ?></h2>
    <?php if ($produits === []): ?>
        <p>Aucun produit trouvé.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Id</th><th>Nom</th><th>Prix</th><th>Stock</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($produits as $produit): ?>
                <tr>
                    <td><?= e($produit->getId()) ?></td>
                    <td><?= e($produit->getNom()) ?></td>
                    <td><?= e(number_format($produit->getPrix(), 2, ',', ' ')) ?> €</td>
                    <td><?= e($produit->getStock()) ?></td>
                    <td>
                        <form class="inline" method="post" action="index.php" onsubmit="return confirm('Supprimer ce produit ?');">
                            <input type="hidden" name="action" value="supprimer">
                            <input type="hidden" name="id" value="<?= e($produit->getId()) ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
