<?php

declare(strict_types=1);

/**
 * Représente un produit de la table produit.
 *
 * Cette classe contient les données et les règles simples de validation.
 * Elle ne contient ni requête SQL ni connexion PDO.
 */
class Produit
{
    public function __construct(
        private ?int $id,
        private string $nom,
        private float $prix,
        private int $stock
    ) {
        $this->setNom($nom);
        $this->setPrix($prix);
        $this->setStock($stock);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    /** Affecte l'identifiant créé par MySQL après un INSERT. */
    public function setId(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("L'identifiant doit être positif.");
        }

        $this->id = $id;
    }

    public function setNom(string $nom): void
    {
        $nom = trim($nom);

        if ($nom === '') {
            throw new InvalidArgumentException('Le nom ne peut pas être vide.');
        }

        $this->nom = $nom;
    }

    public function setPrix(float $prix): void
    {
        if ($prix < 0) {
            throw new InvalidArgumentException('Le prix ne peut pas être négatif.');
        }

        $this->prix = $prix;
    }

    public function setStock(int $stock): void
    {
        if ($stock < 0) {
            throw new InvalidArgumentException('Le stock ne peut pas être négatif.');
        }

        $this->stock = $stock;
    }

    /**
     * Affichage demandé dans les premiers exercices du TP (en mode texte).
     * L'affichage HTML de l'application est réalisé dans index.php.
     */
    public function afficher(): void
    {
        echo sprintf(
            "%d - %s - %.2f euros - stock : %d%s",
            $this->id ?? 0,
            $this->nom,
            $this->prix,
            $this->stock,
            PHP_EOL
        );
    }
}
