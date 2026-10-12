<?php

declare(strict_types=1);

/**
 * Crée la connexion PDO utilisée par les pages de l'application.
 *
 * Les valeurs par défaut correspondent à l'énoncé. Elles peuvent être
 * remplacées sans modifier le code, par exemple : DB_PORT=8889 et
 * DB_PASSWORD=root pour une installation MAMP classique.
 */
function creerConnexionPDO(): PDO
{
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '8889';
    $dbname = getenv('DB_NAME') ?: 'boutique';
    $user = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: 'root';

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

    return new PDO(
        $dsn,
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
}
