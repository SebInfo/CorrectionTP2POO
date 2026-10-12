<?php

declare(strict_types=1);

/**
 * Centralise toutes les requêtes SQL concernant les produits.
 *
 * Une page n'a donc pas besoin de connaître SQL ni PDO : elle appelle les
 * méthodes du Repository et reçoit des objets Produit.
 */
class ProduitRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /** Transforme une ligne SQL (tableau associatif) en objet Produit. */
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
        $sql = 'SELECT id, nom, prix, stock FROM produit WHERE id = :id';
        $requete = $this->pdo->prepare($sql);
        $requete->execute(['id' => $id]);

        $ligne = $requete->fetch();

        return $ligne === false ? null : $this->hydrater($ligne);
    }

    /** @return Produit[] */
    public function findAll(): array
    {
        $requete = $this->pdo->query(
            'SELECT id, nom, prix, stock FROM produit ORDER BY id'
        );

        $produits = [];
        foreach ($requete->fetchAll() as $ligne) {
            $produits[] = $this->hydrater($ligne);
        }

        return $produits;
    }

    /** @return Produit[] */
    public function findByNom(string $recherche): array
    {
        $sql = 'SELECT id, nom, prix, stock
                FROM produit
                WHERE nom LIKE :recherche
                ORDER BY id';
        $requete = $this->pdo->prepare($sql);
        $requete->execute(['recherche' => '%' . $recherche . '%']);

        $produits = [];
        foreach ($requete->fetchAll() as $ligne) {
            $produits[] = $this->hydrater($ligne);
        }

        return $produits;
    }

    public function insert(Produit $produit): void
    {
        $sql = 'INSERT INTO produit (nom, prix, stock)
                VALUES (:nom, :prix, :stock)';
        $requete = $this->pdo->prepare($sql);
        $requete->execute([
            'nom' => $produit->getNom(),
            'prix' => $produit->getPrix(),
            'stock' => $produit->getStock(),
        ]);

        // Avant l'insertion, MySQL n'a pas encore créé l'identifiant.
        $produit->setId((int) $this->pdo->lastInsertId());
    }

    public function update(Produit $produit): void
    {
        if ($produit->getId() === null) {
            throw new LogicException('Impossible de modifier un produit sans identifiant.');
        }

        $sql = 'UPDATE produit
                SET nom = :nom, prix = :prix, stock = :stock
                WHERE id = :id';
        $requete = $this->pdo->prepare($sql);
        $requete->execute([
            'id' => $produit->getId(),
            'nom' => $produit->getNom(),
            'prix' => $produit->getPrix(),
            'stock' => $produit->getStock(),
        ]);
    }

    public function delete(int $id): void
    {
        $requete = $this->pdo->prepare('DELETE FROM produit WHERE id = :id');
        $requete->execute(['id' => $id]);
    }
}
