<?php

declare(strict_types=1);

require_once __DIR__ . '/../connexion.php';
require_once __DIR__ . '/../ProduitRepository.php';
require_once __DIR__ . '/../Produit.php';

$repository = new ProduitRepository(creerConnexionPDO());
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id !== false && $id > 0) {
        $repository->delete($id); // delete() utilise DELETE ... WHERE id = :id
        $verification = $repository->findById($id);
        $message = $verification === null
            ? "Le produit {$id} a été supprimé (vérification réussie)."
            : "Le produit {$id} existe encore.";
    } else {
        $message = 'Identifiant invalide.';
    }
}

/*
 * Dangereux : $sql = "DELETE FROM produit WHERE id = " . $_GET['id'];
 * Une saisie comme "1 OR 1=1" peut modifier la requête et supprimer plusieurs
 * lignes. Une requête préparée envoie le SQL et la donnée séparément : la valeur
 * est traitée comme une valeur, jamais comme une instruction SQL.
 */
?>
<!doctype html>
<html lang="fr"><meta charset="utf-8"><title>Exercice 9</title>
<body>
    <h1>Exercice 9 — Suppression sécurisée</h1>
    <form method="post"><label>Id à supprimer : <input name="id" type="number" min="1" value="5"></label><button>Supprimer</button></form>
    <p><?= htmlspecialchars($message) ?></p>
</body>
</html>
