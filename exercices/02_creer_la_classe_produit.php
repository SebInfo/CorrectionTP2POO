<?php

declare(strict_types=1);

class Produit
{
    public function __construct(
        private ?int $id,
        private string $nom,
        private float $prix,
        private int $stock
    ) {
    }

    public function getId(): ?int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrix(): float { return $this->prix; }
    public function getStock(): int { return $this->stock; }

    public function afficher(): void
    {
        echo sprintf(
            '%d - %s - %.2f euros - stock : %d',
            $this->id,
            $this->nom,
            $this->prix,
            $this->stock
        );
    }
}

$produit = new Produit(1, 'Clavier mecanique', 79.90, 10);
?>
<!doctype html>
<html lang="fr"><meta charset="utf-8"><title>Exercice 2</title>
<body><h1>Exercice 2 — Classe Produit</h1><p><?php $produit->afficher(); ?></p></body>
</html>
